<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Service;

use Sds\IdentityModule\Controller\IdentityController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IdentityControllerFactory implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \SdsAuthModule\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new IdentityController($serviceLocator->get('Config')['sds']['identity']['identityControllerOptions']);
    }
}
