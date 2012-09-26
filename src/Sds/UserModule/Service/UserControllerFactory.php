<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Service;

use Sds\UserModule\Controller\UserController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UserControllerFactory implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \SdsAuthModule\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $controller = new UserController;

        $config = $serviceLocator->get('Config')['sds']['user'];

        $documentManager = $config['documentManager'];
        if (is_string($documentManager)) {
            $documentManager = $serviceLocator->get($documentManager);
        }
        $controller->setDocumentManager($documentManager);

        $serializer = $config['serializer'];
        if (is_string($serializer)) {
            $serializer = $serviceLocator->get($serializer);
        }
        $controller->setSerializer($serializer);

        $validator = $config['validator'];
        if (is_string($validator)) {
            $validator = $serviceLocator->get($validator);
        }
        $controller->setValidator($validator);

        $controller->setUserClass($config['userClass']);

        return $controller;
    }
}
