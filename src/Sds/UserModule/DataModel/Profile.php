<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\DataModel;

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
 * @Sds\Serializer(@Sds\ClassName)
 * @Sds\Dojo(@Sds\ClassName)
 */
class Profile
{
    /**
     * @ODM\Field(type="string")
     * @Sds\CryptHash
     * (
     *     saltClass = ""
     * )
     * @Sds\ValidatorGroup(
     *     @Sds\Validator(class = "Sds\Common\Validator\EmailAddressValidator")
     * )
     * @Sds\Dojo(
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/EmailAddressValidator")
     *     )
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