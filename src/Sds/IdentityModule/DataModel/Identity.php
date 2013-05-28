<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\DataModel;

use Sds\Common\Identity\CredentialInterface;
use Sds\Common\Identity\IdentityInterface;
use Sds\Common\Identity\RoleAwareIdentityInterface;
use Sds\DoctrineExtensions\Identity\DataModel\CredentialTrait;
use Sds\DoctrineExtensions\Identity\DataModel\IdentityTrait;
use Sds\DoctrineExtensions\Identity\DataModel\RoleAwareIdentityTrait;

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
 * @ODM\Document
 * @Sds\Serializer\ClassName
 * @Sds\Permission\Basic(roles="all", allow={"read", "create"})
 * @Sds\Permission\Basic(roles="owner", allow="update")
 * @Sds\Permission\Basic(roles="forgotCredentialController", allow="updateCredential")
 * @Sds\Permission\Basic(roles="admin", allow={"delete", "updateRoles"})
 *
 */
class Identity implements
    CredentialInterface,
    IdentityInterface,
    RoleAwareIdentityInterface
{

    use CredentialTrait;
    use IdentityTrait;
    use RoleAwareIdentityTrait;

    /**
     * @ODM\String
     * @Sds\Validator\Required
     * @Sds\Validator\PersonalName
     */
    protected $firstname;

    /**
     * @ODM\Field(type="string")
     * @Sds\Validator\Required
     * @Sds\Validator\PersonalName
     */
    protected $lastname;

    /**
     * @ODM\String
     * @Sds\Serializer\Ignore("ignore_when_serializing")
     * @Sds\Crypt\BlockCipher(
     *     keyClass = "Sds\IdentityModule\Crypt\Email",
     *     saltClass = "Sds\IdentityModule\Crypt\Email"
     * )
     * @Sds\Validator\Required
     * @Sds\Validator\EmailAddress
     */
    protected $email;

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = (string) $email;
    }
}
