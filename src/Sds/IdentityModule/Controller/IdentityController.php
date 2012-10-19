<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Controller;

use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Crypt\BlockCipherService;
use Sds\JsonController\AbstractJsonRpcController;
use Sds\IdentityModule\Exception\InvalidArgumentException;
use Sds\IdentityModule\Exception\IdentityNotFoundException;
use Sds\IdentityModule\Options\IdentityController as IdentityControllerOptions;
use Zend\Mail\Message;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IdentityController extends AbstractJsonRpcController
{

    protected $options;

    public function getOptions() {
        return $this->options;
    }

    public function setOptions($options) {
        if (!$options instanceof IdentityControllerOptions) {
            $options = new IdentityControllerOptions($options);
        }
        isset($this->serviceLocator) ? $options->setServiceLocator($this->serviceLocator) : null;
        $this->options = $options;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        parent::setServiceLocator($serviceLocator);
        $this->getOptions()->setServiceLocator($serviceLocator);
    }

    /**
     * {@inheritdoc}
     */
    public function registerRpcMethods(){
        return array(
            'forgotCredentialPart1',
            'forgotCredentialPart2',
            'register',
            'identityNameAvailable'
        );
    }

    public function __construct($options = null) {
        $this->setOptions($options);
    }

    /**
     *
     * @param string $identityName
     * @param string $email
     * @throws InvalidArgumentException
     * @throws IdentityNotFoundException
     * @return boolean
     */
    public function forgotCredentialPart1($identityName = null, $email = null)
    {
        $options = $this->getOptions();
        $identityClass = $options->getIdentityClass();
        $documentManager = $options->getDocumentManager();
        $metadata = $documentManager->getClassMetadata($identityClass);
        $criteria = [];

        if ( isset($identityName) && ! $identityName == ''){
            $criteria['identityName'] = $identityName;
        }

        if ( isset($email) && $email != ''){
            $criteria['email'] = BlockCipherService::encryptFieldValue(
                'email',
                $email,
                $metadata
            );
        }

        if (count($criteria) == 0){
            throw new InvalidArgumentException('Either identityName or email must be provided');
        }

        $repository = $documentManager->getRepository($identityClass);
        $results = $repository->findBy($criteria);
        if (count($results) != 1){
            throw new IdentityNotFoundException();
        }

        // create unique recovery code
        $code = Hash::hash(time(), $identityName) ;

        $forgotCredentialExpiry = $options->getForgotCredentialExpiry();
        $identity = $results->getNext();
        $identity->setForgotCredentialExpires(time() + $forgotCredentialExpiry);
        $identity->setForgotCredentialCode($code);

        $documentManager->flush();

        $link = str_replace('[code]', $code, $options->getForgotCredentialLink());
        $link = str_replace('[identityName]', $identity->getIdentityName(), $link);

        // Create email body
        $body = new ViewModel([
            'identityName' => $identity->getIdentityName(),
            'link' => $link,
            'hours' => $forgotCredentialExpiry / (60 * 60) //Convert expiry from seconds to hours
        ]);
        $body->setTemplate('email/forgot-credential');

        // Send the email
        $mail = new Message();
        $mail->setBody($this->getEmailRenderer()->render($body))
            ->setFrom($this->mailFrom)
            ->addTo(BlockCipherService::decryptValue($identity->getEmail(), $metadata->{Sds\CryptBlockCipher::metadataKey}['email']))
            ->setSubject($options->getForgotCredentialEmailSubject());

        $this->getMailTransport()->send($mail);

        return true;
    }

    /**
     *
     * @param string $identityName
     * @param string $newCredential
     * @param string $forgotCredentialCode
     * @return boolean
     * @throws IdentityNotFoundException
     * @throws InvalidArgumentException
     */
    public function forgotCredentialPart2($identityName, $newCredential, $forgotCredentialCode)
    {
        $options = $this->getOptions();
        $documentManager = $options->getDocumentManager();

        $identity = $documentManager->createQueryBuilder($this->identityClass)
            ->field('identityName')->equals($identityName)
            ->field('forgotCredentialCode')->equals($forgotCredentialCode)
            ->field('forgotCredentialExpires')->gt(time())
            ->getQuery()
            ->getSingleResult();

        if ( ! isset($identity)){
            throw new IdentityNotFoundException();
        }

        $identity->setPassword($newCredential);

        // Check that the new password is valid before flushing
        $validator = $this->getDocumentValidator();
        if ( ! $validator->isValid($identity, $documentManager->getClassMetadata($options->getIdentityClass()))){
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
        $options = $this->getOptions();
        $documentManager = $options->getDocumentManager();
        $validator = $options->getDocumentValidator();
        $serializer = $options->getSerializer();

        $newIdentity = $serializer->fromArray($data);
        if ( ! $validator->isValid($newIdentity, $documentManager->getClassMetadata($options->getIdentityClass()))){
            throw new InvalidArgumentException(implode(', ', $validator->getMessages()));
        }

        $documentManager->persist($newIdentity);
        $documentManager->flush();

        return $serializer->toArray($newIdentity);
    }

    /**
     *
     * @param object $identityName
     * @return boolean
     */
    public function identityNameAvailable($identityName){

        $options = $this->getOptions();
        $repository = $options->getDocumentManager()->getRepository($options->getIdentityClass());
        $results = $repository->findBy(['identityName' => $identityName]);
        if (count($results) > 0){
            return false;
        }
        return true;
    }
}

