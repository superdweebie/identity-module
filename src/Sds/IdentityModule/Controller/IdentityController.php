<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Controller;

use Sds\DoctrineExtensionsModule\Controller\JsonRestfulController;
use Sds\IdentityModule\Exception;
use Sds\IdentityModule\Options\IdentityController as Options;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IdentityController extends JsonRestfulController
{

    /**
     * Don't allow updates to credential - must use the ForgotCredentialToken instead
     *
     * @param type $id
     * @param type $data
     * @return type
     * @throws InvalidArgumentException
     */
    public function update($id, $data){

        if (isset($data['credential'])){
            throw new Exception\InvalidArgumentException('Credential cannot be updated. Use ForgotCredentialToken instead.');
        }
        return parent::update($id, $data);
    }
}

