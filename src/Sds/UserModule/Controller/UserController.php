<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Controller;

use Sds\Common\Serializer\SerializerInterface;
use Sds\DoctrineExtensions\Validator\DocumentValidatorInterface;
use Sds\JsonController\AbstractJsonRpcController;
use Sds\UserModule\Exception\InvalidArgumentException;
use Sds\UserModule\Exception\UserNotFoundException;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

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

    public function setSerializer(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    public function getSerializer() {
        return $this->serializer;
    }

    public function getValidator() {
        return $this->validator;
    }

    public function setValidator(DocumentValidatorInterface $validator) {
        $this->validator = $validator;
    }

    public function getDocumentManager() {
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
        return $this->mailTransport;
    }

    public function setMailTransport(TransportInterface $mailTransport) {
        $this->mailTransport = $mailTransport;
    }

    /**
     * {@inheritdoc}
     */
    public function registerRpcMethods(){
        return array(
            'recoverPassword',
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
    public function recoverPassword($username, $email)
    {

        $criteria = [];

        if ( ! $username == ''){
            $criteria['username'] = $username;
        }

        if ( ! $email != ''){
            $criteria['email'] = $email;
        }

        if (count($criteria) == 0){
            throw new InvalidArgumentException('Either username or email must be provided');
        }

        $repository = $this->documentManager->getRepository($this->userClass);
        $results = $repository->findBy($critiera);
        if (count($results) != 1){
            throw new UserNotFoundException();
        }

        $user = $results[0];

        $mail = new Message();
        $mail->setBody('Recover password request')
            ->setFrom('usermodule@sds.com')
            ->addTo($user->getProfile()->getEmail())
            ->setSubject('Password Recovery');

        $this->transport->send($mail);

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
        $newUser = $this->serializer->fromArray($data);
        if ( ! $this->validator->isValid($newUser, $this->documentManager->getClassMetadata($this->userClass))){
            throw new InvalidArgumentException(implode(', ', $this->validator->getMessages()));
        }

        $this->documentManager->persist($newUser);
        $this->documentManager->flush();

        return $this->serializer->toArray($newUser);
    }

    /**
     *
     * @param object $username
     * @return boolean
     */
    public function usernameAvailable($username){

        $repository = $this->documentManager->getRepository($this->userClass);
        $results = $repository->findBy(['username' => $username]);
        if (count($results) > 0){
            return false;
        }
        return true;
    }
}

