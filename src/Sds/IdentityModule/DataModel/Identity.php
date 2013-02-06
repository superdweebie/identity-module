<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\DataModel;

use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\AccessControl\Constant\Action;
use Sds\Common\AccessControl\Constant\Role;
use Sds\Common\Identity\CredentialInterface;
use Sds\Common\Identity\IdentityInterface;
use Sds\Common\Identity\RoleAwareIdentityInterface;
use Sds\DoctrineExtensions\AccessControl\DataModel\AccessControlledTrait;
use Sds\DoctrineExtensions\AccessControl\DataModel\Permission;
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
 * @Sds\Serializer(@Sds\ClassName)
 * @Sds\Rest
 * @Sds\Generator({
 *     @Sds\Dojo\Model,
 *     @Sds\Dojo\JsonRest,
 *     @Sds\Dojo\ModelValidator
 * })
 */
class Identity implements
    CredentialInterface,
    IdentityInterface,
    RoleAwareIdentityInterface,
    AccessControlledInterface
{
    use AccessControlledTrait;
    use CredentialTrait;
    use IdentityTrait {
        IdentityTrait::setIdentityName as traitSetIdentityName;
    }
    use RoleAwareIdentityTrait;

    /**
     * @ODM\String
     * @Sds\Serializer(@Sds\Ignore("ignore_when_serializing"))
     * @Sds\CryptBlockCipher(
     *     keyClass = "Sds\IdentityModule\Crypt\Email",
     *     saltClass = "Sds\IdentityModule\Crypt\Email"
     * )
     * @Sds\Validator\Required,
     * @Sds\Validator\EmailAddress
     * @Sds\Generator({
     *     @Sds\Dojo\Input
     * })
     */
    protected $email;

    /**
     * @ODM\ReferenceOne(
     *     targetDocument = "Profile",
     *     mappedBy = "identityName",
     *     simple = true,
     *     cascade = "all"
     * )
     */
    protected $profile;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = (string) $email;
    }

    public function getProfile() {
        return $this->profile;
    }

    public function setProfile(Profile $profile) {
        $this->profile = $profile;
    }

    public function __construct() {
        $this->setPermissions([
            new Permission(Role::guest, Action::read),
            new Permission(Role::guest, Action::create),
            new Permission(Role::admin, Action::delete),
            new Permission(Role::superAdmin, Action::update)
        ]);

        $this->setRoles([Role::guest, Role::user]);
    }

    public function setIdentityName($identityName){
        $this->traitSetIdentityName($identityName);

        $identityRole = 'identity-' . $identityName;

        //Add permission so that only an identity is allowed to update themselves
        $this->addPermission(
            new Permission($identityRole, Action::update)
        );

        $this->addRole($identityRole);
    }
}
