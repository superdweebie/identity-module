<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Options;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\View\Renderer\PhpRenderer;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IdentityController extends AbstractOptions
{

    protected $serviceLocator;

    protected $serializer;

    protected $documentValidator;

    protected $documentManager;

    protected $identityClass;

    protected $mailTransport;

    protected $mailFrom;

    protected $emailRenderer;

    protected $forgotCredentialLink;

    protected $forgotCredentialExpiry;

    protected $forgotCredentialEmailSubject;

    protected $limit;

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

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

    public function getDocumentValidator() {
        if (is_string($this->documentValidator)) {
            $this->documentValidator = $this->serviceLocator->get($this->documentValidator);
        }
        return $this->documentValidator;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Validator\DocumentValidatorInterface | string $validator
     */
    public function setDocumentValidator($documentValidator) {
        $this->documentValidator = $documentValidator;
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

    public function getIdentityClass() {
        return $this->identityClass;
    }

    public function setIdentityClass($identityClass) {
        $this->identityClass = $identityClass;
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

    public function getForgotCredentialLink() {
        return $this->forgotCredentialLink;
    }

    public function setForgotCredentialLink($forgotCredentialLink) {
        $this->forgotCredentialLink = (string) $forgotCredentialLink;
    }

    public function getForgotCredentialExpiry() {
        return $this->forgotCredentialExpiry;
    }

    public function setForgotCredentialExpiry($forgotCredentialExpiry) {
        $this->forgotCredentialExpiry = $forgotCredentialExpiry;
    }

    public function getForgotCredentialEmailSubject() {
        return $this->forgotCredentialEmailSubject;
    }

    public function setForgotCredentialEmailSubject($forgotCredentialEmailSubject) {
        $this->forgotCredentialEmailSubject = (string) $forgotCredentialEmailSubject;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        $this->limit = (int) $limit;
    }
}
