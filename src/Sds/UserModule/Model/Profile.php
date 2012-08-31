<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Model;

use Sds\Common\User\AuthInterface;
use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\User\Behaviour\AuthTrait;
use Sds\DoctrineExtensions\User\Behaviour\RoleAwareUserTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @ODM\EmbeddedDocument
 * @Sds\SerializeClassName
 * @Sds\ClassDojo(
 *     className = true
 * )
 */
class Profile
{
    /**
     * @ODM\Field(type="string")
     * @Sds\CryptHash
     * (
     *     saltClass = ""
     * )
     * @Sds\PropertyValidators({
     *     @Sds\Validator(class = "Sds\Common\Validator\EmailAddressValidator")
     * })
     * @Sds\PropertyDojo(
     *     required = true,
     *     validators = {@Sds\DojoValidator(module = "Sds/Validator/EmailAddressValidator")}
     * )
     */
    protected $email;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = (string) $email;
    }
}