<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Controller;

use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Crypt\BlockCipherService;
use Sds\JsonController\AbstractJsonRestfulController;
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
class IdentityController extends AbstractJsonRestfulController
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

    public function __construct($options = null) {
        $this->setOptions($options);
    }

    public function getList(){

        $queryBuilder = $this->documentManager->createQueryBuilder();
        $queryBuilder
            ->find($this->options->getIdentityClass())
            ->limit($this->getLimit())
            ->skip($this->getOffset())
            ->sort($this->getSort(), $this->getOrder())
            ->hydrate(false)
            ->eagerCursor(true);

        $results = $queryBuilder->getQuery()->execute();

        foreach ($results as $index => $result){
            $results[$index] = $this->options->getSerializer()->applySerializeMetadataToArray($result, $this->options->getIdentityClass());
        }

        return $results;
    }

    public function get($identityName){

        $queryBuilder = $this->getDocumentManager()->createQueryBuilder();
        $queryBuilder
            ->find($this->options->getIdentityClass())
            ->field('identityName')->equals($identityName)
            ->hydrate(false)
            ->eagerCursor(true);

        return $this->serializer->applySerializeMetadataToArray(
            $queryBuilder->getQuery()->getSingleResult(),
            $this->options->getIdentityClass()
        );
    }

    public function create($data){

        $documentManager = $this->options->getDocumentManager();
        $serializer = $this->options->getSerializer();

        $identity = $serializer->fromArray($data);
        $validatorResult = $this->options->getDocumentValidator()
            ->isValid($identity, $documentManager->getClassMetadata($this->options->getIdentityClass()));

        if ( ! $validatorResult->getResult()){
            throw new InvalidArgumentException(implode(', ', $validatorResult->getMessages()));
        }

        $documentManager->persist($identity);
        $documentManager->flush();

        return $serializer->toArray($identity);
    }

    public function update($data){

        $documentManager = $this->options->getDocumentManager();
        $serializer = $this->options->getSerializer();

        $identity = $serializer->fromArray($data, null, $this->options->getIdentityClass());

        $validatorResult = $this->options->getDocumentValidator()
            ->isValid($identity, $documentManager->getClassMetadata($this->options->getIdentityClass()));

        if ($validatorResult->getResult()) {
            $queryBuilder = $documentManager->createQueryBuilder();
            $queryBuilder
                ->update($this->options->getIdentityClass())
                ->field('identityName')->equals($id);


            $this->documentManager->persist($document);
        } else {
            throw new \Exception('Problem creating');
        }
    }

    public function delete($identityName){
    
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

    protected function getLimit(){

        $range = $this->getRequest()->getHeader('Range');

        if (isset($range)) {
            $range = explode('=', $range);
            $range = explode('-', $range[1]);
            if ((string)(int)$range[0] == $range[0] && (string)(int)$range[1] == $range[1])
            {
                $limit = $range[1] - $range[0] + 1;
                if ($limit < $this->options->getLimit()) {
                    return $limit;
                }
            }
        }
        return $this->options->getLimit();
    }

    protected function getOffset(){

        $range = $this->getRequest()->getHeader('Range');

        if(isset($range)){
            $range = explode('=', $range);
            $range = explode('-', $range[1]);
            return  intval($range[0]);
        } else {
            return 0;
        }
    }

    protected function getSort(){

        foreach ($options as $key => $value)
        {
            if(substr($key, 0, 4) == 'sort')
            {
                $sortkey = $key;
                $sort = substr($key, 6, strlen($key) - 7);
                $order = substr($key, 5,1);
            }
        }
        switch ($order)
        {
            case '_':
                $order = 'ASC';
                break;
            case '-':
                $order = 'DESC';
        }
        return array($sortkey, $sort, $order);
    }
}

