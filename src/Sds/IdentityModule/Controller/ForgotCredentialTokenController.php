<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Controller;

use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Crypt\BlockCipherService;
use Sds\DoctrineExtensionsModule\Controller\JsonRestfulController;
use Sds\IdentityModule\Exception;
use Sds\IdentityModule\Options\ForgotCredentialTokenController as Options;
use Zend\Http\Header\Allow;
use Zend\Http\Response;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ForgotCredentialTokenController extends JsonRestfulController
{

    public function getOptions() {
        return $this->options;
    }

    public function setOptions(Options $options) {
        $this->options = $options;
    }

    public function __construct(Options $options = null) {
        if (!isset($options)){
            $options = new Options;
        }
        $this->setOptions($options);
    }

    /**
     * This will start the credential reset process for an identity.
     * If the identity is found in the db, a new token is created, and
     * that token is sent to the identity's email.
     *
     * @param type $data
     * @return type
     * @throws Exception\LoginFailedException
     */
    public function create($data){

        $documentManager = $this->options->getDocumentManager();
        $identityMetadata = $documentManager->getClassMetadata($this->options->getIdentityClass());

        $criteria = [];
        if ( isset($data['identityName']) && ! $data['identityName'] == ''){
            $criteria['identityName'] = $data['identityName'];
        }

        if ( isset($data['email']) && $data['email'] != ''){
            $criteria['email'] = BlockCipherService::encryptValue(
                $data['email'],
                $identityMetadata->crypt['blockCipher']['email']
            );
        }

        if (count($criteria) == 0){
            throw new Exception\InvalidArgumentException('Either identityName or email must be provided');
        }

        $identityRepository = $documentManager->getRepository($this->options->getIdentityClass());
        $identity = $identityRepository->findOneBy($criteria);
        if ( ! isset($identity)){
            throw new Exception\DocumentNotFoundException();
        }

        // create unique recovery code
        $code = Hash::hash(time(), $identity->getIdentityName());

        $expiry = $this->options->getExpiry();

        // delete any existing tokens for the identity
        $documentManager
            ->createQueryBuilder($this->options->getDocumentClass())
            ->remove()
            ->field('identityName')->equals($identity->getIdentityName())
            ->getQuery()
            ->execute();

        parent::create([
            'code' => $code,
            'identityName' => $identity->getIdentityName(),
            'expires' => $expiry + time()
        ]);

        $link = '/forgotCredentialToken/' . $code;

        // Create email body
        $body = new ViewModel([
            'identityName' => $identity->getIdentityName(),
            'link' => $link,
            'hours' => $expiry / (60 * 60) //Convert expiry from seconds to hours
        ]);
        $body->setTemplate('email/forgot-credential');

        // Send the email
        $mail = new Message();
        $mail->setBody($this->options->getEmailRenderer()->render($body))
            ->setFrom($this->options->getMailFrom())
            ->addTo(BlockCipherService::decryptValue($identity->getEmail(), $identityMetadata->crypt['blockCipher']['email']))
            ->setSubject($this->options->getMailSubject());

        $this->options->getMailTransport()->send($mail);

        $this->response->setStatusCode(201);
        return $this->response;
    }

    /**
     * This completes the credential reset process.
     *
     * @param type $code
     * @param type $data
     * @return type
     */
    public function update($id, $data) {

        $documentManager = $this->options->getDocumentManager();
        $token = $documentManager->createQueryBuilder($this->options->getDocumentClass())
            ->field('code')->equals($id)
            ->field('expires')->gt(new \DateTime)
            ->getQuery()
            ->getSingleResult();

        if ( ! isset($token)){
            throw new Exception\DocumentNotFoundException();
        }

        $identity = $documentManager->getRepository($this->options->getIdentityClass())->findOneBy(['identityName' => $token->getIdentityName()]);

        $identity->setCredential($data['credential']);

        $documentValidator = $this->options->getDocumentValidator();
        $documentValidator->setDocumentManager($documentManager);
        $validatorResult = $documentValidator->isValid(
            $identity,
            $documentManager->getClassMetadata($this->options->getIdentityClass())
        );

        if ( ! $validatorResult->getResult()){
            throw new Exception\InvalidArgumentException(implode(', ', $validatorResult->getMessages()));
        }

        $documentManager->remove($token);
        $documentManager->flush();

        $this->response->setStatusCode(201);
        return $this->response;
    }

    /**
     * Tokens cannot be listed
     *
     * @return type
     */
    public function getList(){
        $allow = new Allow;
        $allow->allowMethods(Response::POST);
        $this->response->setStatusCode(405);
        $this->response->getHeaders()->addHeader($allow);
        return $this->response;
    }

    /**
     * Tokens cannot be got
     *
     * @param type $id
     * @return type
     */
    public function get($id){
        $allow = new Allow;
        $allow->allowMethods(Response::PUT);
        $this->response->setStatusCode(405);
        $this->response->getHeaders()->addHeader($allow);
        return $this->response;
    }

    /**
     * Tokens cannot be deleted through the API.
     *
     * @param type $id
     */
    public function deleteList() {
        $allow = new Allow;
        $allow->allowMethods(Response::POST);
        $this->response->setStatusCode(405);
        $this->response->getHeaders()->addHeader($allow);
        return $this->response;
    }

    /**
     * Tokens cannot be deleted through the API.
     *
     * @param type $id
     */
    public function delete($id) {
        $allow = new Allow;
        $allow->allowMethods(Response::PUT);
        $this->response->setStatusCode(405);
        $this->response->getHeaders()->addHeader($allow);
        return $this->response;
    }

    /**
     * Tokens cannot be modified through the API.
     *
     * @param type $id
     */
    public function patchList() {
        $allow = new Allow;
        $allow->allowMethods(Response::POST);
        $this->response->setStatusCode(405);
        $this->response->getHeaders()->addHeader($allow);
        return $this->response;
    }

    /**
     * Tokens cannot be modified through the API.
     *
     * @param type $id
     */
    public function patch($id) {
        $allow = new Allow;
        $allow->allowMethods(Response::PUT);
        $this->response->setStatusCode(405);
        $this->response->getHeaders()->addHeader($allow);
        return $this->response;
    }
}
