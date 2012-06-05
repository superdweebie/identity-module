<?php

namespace SdsUserModule;

use Zend\ModuleManager\ModuleManager;

class Module
{
    public function init(ModuleManager $mm){
        $sharedEvents = $mm->events()->getSharedManager();
        $sharedEvents->attach('DoctrineMongoODMModule', 'loadDrivers', array($this, 'loadMongoODMDrivers'));            
    }
    
    public function getConfig(){
        return include __DIR__ . '/../../config/module.config.php';
    }   
    
    public function loadMongoODMDrivers($e){
        $serviceLocator = $e->getTarget();
        $reader = $serviceLocator->get('Doctrine\Common\Annotations\CachedReader');
        $config = $serviceLocator->get('Configuration')['sds_user_config']['drivers'];
        $return = array();
        
        foreach($config as $params){
            $return[$params['namespace']] = new $params['class']($reader, $params['paths']);
        }   
        return $return;
    }   
    
    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'guestUser' => 'SdsUserModule\Service\GuestUserFactory',
            )
        );
    }      
}