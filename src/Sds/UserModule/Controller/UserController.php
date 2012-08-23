<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Controller;

use Sds\JsonController\AbstractJsonRpcController;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UserController extends AbstractJsonRpcController
{

    /**
     * {@inheritdoc}
     */
    public function registerRpcMethods(){
        return array(
            'recoverPassword',
            'register'
        );
    }

    /**
     *
     * @param string $username
     * @param string $email
     * @return object
     */
    public function recoverPassword($username, $email)
    {

    }

    /**
     *
     * @param object $newUser
     * @return object
     */
    public function register($newUser)
    {
    }
}

