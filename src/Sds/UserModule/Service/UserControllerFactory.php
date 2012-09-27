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

        //set document manager
        $controller->setDocumentManager($config['documentManager']);

        //set serializer
        $controller->setSerializer($config['serializer']);

        //set validator
        $controller->setValidator($config['validator']);

        //set mailTransport
        $controller->setMailTransport($config['mailTransport']);

        $controller->setUserClass($config['userClass']);
        $controller->setMailFrom($config['mailFrom']);
        $controller->setRecoverPasswordLink($config['recoverPasswordLink']);
        $controller->setRecoverPasswordExpiry($config['recoverPasswordExpiry']);

        return $controller;
    }
}
