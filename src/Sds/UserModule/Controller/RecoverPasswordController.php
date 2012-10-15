<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class RecoverPasswordController extends AbstractActionController
{
    public function recoverPasswordAction(){
        return new ViewModel();
    }
}
