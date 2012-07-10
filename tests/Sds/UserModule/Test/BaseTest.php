<?php

namespace Sds\UserModule\Test;

use PHPUnit_Framework_TestCase;
use Zend\Mvc\Service\ServiceManagerConfiguration;
use Zend\ServiceManager\ServiceManager;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{

    protected $serviceManager;

    protected static $serviceConfig;

    public function setup(){

        $serviceConfig = $this->getServiceConfig();

        // $configuration is loaded from TestConfiguration.php (or .dist)
        $serviceManager = new ServiceManager(new ServiceManagerConfiguration($serviceConfig['service_manager']));
        $serviceManager->setService('ApplicationConfiguration', $serviceConfig);
        $serviceManager->setAllowOverride(true);


        $this->serviceManager = $serviceManager;

        /** @var $moduleManager \Zend\ModuleManager\ModuleManager */
        $moduleManager = $serviceManager->get('ModuleManager');
        $moduleManager->loadModules();

        $serviceManager->setService('Configuration', $this->alterConfig($serviceManager->get('Configuration')));
    }

    /**
     * @var array $config
     * @return array
     */
    abstract protected function alterConfig(array $config);

    public static function setServiceConfig(array $serviceConfig)
    {
        self::$serviceConfig = $serviceConfig;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceConfig()
    {
    	return self::$serviceConfig;
    }
}
