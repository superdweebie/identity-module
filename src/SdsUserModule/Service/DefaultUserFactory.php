<?php

namespace SdsUserModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SdsUserModule\Model\User;

class DefaultUserFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration')['sdsUserConfig']['defaultUser'];         
        $instance = new User();
        $instance->setIsGuest(true);
        $instance->addRoles($config['roles']);
        $instance->setUsername($config['username']);        
        return $instance;        
    }
}
