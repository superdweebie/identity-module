<?php

namespace SdsUserModule;

use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\Event;
use SdsInitalizerModule\Service\Events as InitalizerEvents;
use SdsCommon\ActiveUser\DefaultUserAwareInterface;

class Module
{

    public function init(ModuleManager $moduleManager){
        $sharedEvents = $moduleManager->events()->getSharedManager();
        $sharedEvents->attach(
            InitalizerEvents::IDENTIFIER, 
            InitalizerEvents::LOAD_CONTROLLER_LOADER_INITALIZERS, 
            array($this, 'loadInitalizers')
        );
        $sharedEvents->attach(
            InitalizerEvents::IDENTIFIER, 
            InitalizerEvents::LOAD_SERVICE_MANAGER_INITALIZERS, 
            array($this, 'loadInitalizers')
        );        
    }
    
    public function loadInitalizers(Event $e){
        $serviceLocator = $e->getTarget();        
        return array(
            'DefaultUserAwareInterface' =>
            function ($instance) use ($serviceLocator) {
                if ($instance instanceof DefaultUserAwareInterface) {
                    $instance->setDefaultUser($serviceLocator->get('SdsUserModule\DefaultUser'));
                }
            }                  
        );
    }
    
    public function getConfig(){
        return include __DIR__ . '/../../config/module.config.php';
    }   
        
    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'SdsUserModule\DefaultUser' => 'SdsUserModule\Service\DefaultUserFactory',
            )
        );
    }      
}