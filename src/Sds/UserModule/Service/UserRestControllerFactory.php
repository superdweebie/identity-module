<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sds\UserModule\Controller\UserRestController;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UserRestControllerFactory implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \SdsUserModule\Controller\UserRestController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $config = $serviceLocator->get('Config')['sds']['user'];

        $controller = new UserRestController;
        $controller->setDocumentManager($serviceLocator->get($config['documentManager']));

        return $controller;
    }
}