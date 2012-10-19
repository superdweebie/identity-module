<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sds\IdentityModule\Controller\IdentityRestController;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IdentityRestControllerFactory implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \SdsIdentityModule\Controller\IdentityRestController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $config = $serviceLocator->get('Config')['sds']['user'];

        $controller = new IdentityRestController;
        $controller->setDocumentManager($serviceLocator->get($config['documentManager']));

        return $controller;
    }
}