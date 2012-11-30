<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Options;

use Sds\DoctrineExtensionsModule\Options\JsonRestfulController;
use Zend\View\Renderer\PhpRenderer;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ForgotCredentialTokenController extends JsonRestfulController
{

    protected $identityClass;

    protected $mailTransport;

    protected $mailFrom;

    protected $expiry;

    protected $mailSubject;

    protected $emailRenderer;

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

    public function getExpiry() {
        return $this->expiry;
    }

    public function setExpiry($expiry) {
        $this->expiry = (integer) $expiry;
    }

    public function getMailSubject() {
        return $this->mailSubject;
    }

    public function setMailSubject($mailSubject) {
        $this->mailSubject = (string) $mailSubject;
    }

    public function getEmailRenderer() {
        if ( ! isset($this->emailRenderer)){
            $this->emailRenderer = new PhpRenderer;
            $this->emailRenderer->setResolver($this->serviceLocator->get('ViewResolver'));
            $this->emailRenderer->setHelperPluginManager($this->serviceLocator->get('ViewHelperManager'));
        }
        return $this->emailRenderer;
    }

    public function setEmailRenderer($emailRenderer) {
        $this->emailRenderer = $emailRenderer;
    }
}
