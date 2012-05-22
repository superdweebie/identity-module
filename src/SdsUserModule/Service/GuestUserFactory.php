<?php

namespace SdsUserModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SdsUserModule\Model\User;

class GuestUserFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration')->sds_user_config->guest_user;         
        $instance = new User();
        $instance->setIsGuest(true);
        $instance->addRoles($config->roles->toArray());
        $instance->setUsername($config->username);        
        return $instance;        
    }
}
