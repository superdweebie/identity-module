<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Controller;

use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Crypt\BlockCipherService;
use Sds\JsonController\AbstractJsonRpcController;
use Sds\UserModule\Exception\InvalidArgumentException;
use Sds\UserModule\Exception\UserNotFoundException;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UserController extends AbstractJsonRpcController
{

    protected $serializer;

    protected $validator;

    protected $documentManager;

    protected $userClass;

    protected $mailTransport;

    protected $mailFrom;

    protected $emailRenderer;

    protected $recoverPasswordLink;

    protected $recoverPasswordExpiry;

    /**
     *
     * @param \Sds\Common\Serializer\SerializerInterface | string $serializer
     */
    public function setSerializer($serializer) {
        $this->serializer = $serializer;
    }

    public function getSerializer() {
        if (is_string($this->serializer)) {
            $this->serializer = $this->serviceLocator->get($this->serializer);
        }
        return $this->serializer;
    }

    public function getValidator() {
        if (is_string($this->validator)) {
            $this->validator = $this->serviceLocator->get($this->validator);
        }
        return $this->validator;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Validator\DocumentValidatorInterface | string $validator
     */
    public function setValidator($validator) {
        $this->validator = $validator;
    }

    public function getDocumentManager() {
        if (is_string($this->documentManager)) {
            $this->documentManager = $this->serviceLocator->get($this->documentManager);
        }
        return $this->documentManager;
    }

    public function setDocumentManager($documentManager) {
        $this->documentManager = $documentManager;
    }

    public function getUserClass() {
        return $this->userClass;
    }

    public function setUserClass($userClass) {
        $this->userClass = $userClass;
    }

    public function getMailTransport() {
        if (is_string($this->mailTransport)) {
            $this->mailTransport = $this->serviceLocator->get($this->mailTransport);
        }
        return $this->mailTransport;
    }

    /**
     *
     * @param \Zend\Mail\Transport\TransportInterface | string $mailTransport
     */
    public function setMailTransport($mailTransport) {
        $this->mailTransport = $mailTransport;
    }

    public function getMailFrom() {
        return $this->mailFrom;
    }

    public function setMailFrom($mailFrom) {
        $this->mailFrom = $mailFrom;
    }

    public function getEmailRenderer() {
        if ( ! isset($this->emailRenderer)){
            $this->emailRenderer = new PhpRenderer;
            $this->emailRenderer->setResolver($this->serviceLocator->get('ViewResolver'));
        }
        return $this->emailRenderer;
    }

    public function setEmailRenderer($emailRenderer) {
        $this->emailRenderer = $emailRenderer;
    }

    public function getRecoverPasswordLink() {
        return $this->recoverPasswordLink;
    }

    public function setRecoverPasswordLink($recoverPasswordLink) {
        $this->recoverPasswordLink = $recoverPasswordLink;
    }

    public function getRecoverPasswordExpiry() {
        return $this->recoverPasswordExpiry;
    }

    public function setRecoverPasswordExpiry($recoverPasswordExpiry) {
        $this->recoverPasswordExpiry = $recoverPasswordExpiry;
    }

    /**
     * {@inheritdoc}
     */
    public function registerRpcMethods(){
        return array(
            'recoverPassword',
            'recoverPasswordComplete',
            'register',
            'usernameAvailable'
        );
    }

    /**
     *
     * @param string $username
     * @param string $email
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * @return boolean
     */
    public function recoverPassword($username = null, $email = null)
    {

        $documentManager = $this->getDocumentManager();
        $metadata = $documentManager->getClassMetadata($this->userClass);
        $criteria = [];

        if ( isset($username) && ! $username == ''){
            $criteria['username'] = $username;
        }

        if ( isset($email) && $email != ''){
            $criteria['email'] = BlockCipherService::encryptFieldValue(
                'email',
                $email,
                $metadata
            );
        }

        if (count($criteria) == 0){
            throw new InvalidArgumentException('Either username or email must be provided');
        }

        $repository = $documentManager->getRepository($this->userClass);
        $results = $repository->findBy($criteria);
        if (count($results) != 1){
            throw new UserNotFoundException();
        }

        // create unique recovery code
        $code = Hash::hash(time(), $username) ;

        $user = $results->getNext();
        $user->setPasswordRecoveryExpires(time() + $this->recoverPasswordExpiry);
        $user->setPasswordRecoveryCode($code);

        $documentManager->flush();

        $link = str_replace('[code]', $code, $this->recoverPasswordLink);
        $link = str_replace('[username]', $user->getUsername(), $link);

        // Create email body
        $body = new ViewModel([
            'username' => $user->getUsername(),
            'link' => $link,
            'hours' => $this->recoverPasswordExpiry
        ]);
        $body->setTemplate('email/recoverPassword');

        // Send the email
        $mail = new Message();
        $mail->setBody($this->getEmailRenderer()->render($body))
            ->setFrom($this->mailFrom)
            ->addTo(BlockCipherService::decryptValue($user->getEmail(), $metadata->{Sds\CryptBlockCipher::metadataKey}['email']))
            ->setSubject('Password Recovery');

        $this->getMailTransport()->send($mail);

        return true;
    }

    /**
     *
     * @param string $username
     * @param string $newPassword
     * @param string $passwordRecoveryCode
     * @return boolean
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function recoverPasswordComplete($username, $newPassword, $passwordRecoveryCode)
    {
        $documentManager = $this->getDocumentManager();

        $user = $documentManager->createQueryBuilder($this->userClass)
            ->field('username')->equals($username)
            ->field('passwordRecoveryCode')->equals($passwordRecoveryCode)
            ->field('passwordRecoveryExpires')->gt(time())
            ->getQuery()
            ->getSingleResult();

        if ( ! isset($user)){
            throw new UserNotFoundException();
        }

        $user->setPassword($newPassword);

        // Check that the new password is valid before flushing
        $validator = $this->getValidator();
        if ( ! $validator->isValid($user, $documentManager->getClassMetadata($this->userClass))){
            throw new InvalidArgumentException(implode(', ', $validator->getMessages()));
        }

        $documentManager->flush();

        return true;
    }

    /**
     *
     * @param object $data
     * @return object
     * @throws InvalidArgumentException
     */
    public function register($data)
    {
        $documentManager = $this->getDocumentManager();
        $validator = $this->getValidator();
        $serializer = $this->getSerializer();

        $newUser = $serializer->fromArray($data);
        if ( ! $validator->isValid($newUser, $documentManager->getClassMetadata($this->userClass))){
            throw new InvalidArgumentException(implode(', ', $validator->getMessages()));
        }

        $documentManager->persist($newUser);
        $documentManager->flush();

        return $serializer->toArray($newUser);
    }

    /**
     *
     * @param object $username
     * @return boolean
     */
    public function usernameAvailable($username){

        $repository = $this->getDocumentManager()->getRepository($this->userClass);
        $results = $repository->findBy(['username' => $username]);
        if (count($results) > 0){
            return false;
        }
        return true;
    }
}

