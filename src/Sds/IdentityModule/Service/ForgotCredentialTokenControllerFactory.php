<?php
/**
 * @package    Sds
 * @license    MIT
 */

namespace Sds\IdentityModule\Service;

use Sds\IdentityModule\Controller\ForgotCredentialTokenController;
use Sds\IdentityModule\Options\ForgotCredentialTokenControllerOptions;
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
        $options = $serviceLocator->getServiceLocator()->get('config')['sds']['identity']['forgot_credential_token_controller_options'];
        $options['service_locator'] = $serviceLocator->getServiceLocator()->get('doctrineExtensions.' . $options['manifest_name'] . '.servicemanager');
        $instance = new ForgotCredentialTokenController(new ForgotCredentialTokenControllerOptions($options));
        return $instance;
    }
}
