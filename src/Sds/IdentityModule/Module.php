<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule;

use Sds\IdentityModule\Crypt\Email;
use Zend\Mvc\MvcEvent;

/**
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Module
{

    /**
     *
     * @param \Zend\EventManager\Event $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $config = $event->getTarget()->getServiceManager()->get('Config')['sds']['identity'];
        Email::setKey($config['email']['key']);
        Email::setSalt($config['email']['salt']);
    }

    public function getConfig(){
        return include __DIR__ . '/../../../config/module.config.php';
    }
}