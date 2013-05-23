<?php
/**
 * @package    Sds
 * @license    MIT
 */

namespace Sds\AuthenticationModule\Service;

use Sds\IdentityModule\Controller\ForgotCredentialTokenController;
use Sds\IdentityModule\Options\ForgotCredentialTokenController as Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ForgotCredentialTokenControllerFactory implements FactoryInterface
{

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $options = new Options($serviceLocator->getServiceLocator()->get('config')['sds']['identity']['forgot_credential_token_controller_options']);
        $options->setServiceLocator($serviceLocator->getServiceLocator());
        $instance = new ForgotCredentialTokenController($options);
        return $instance;
    }
}
