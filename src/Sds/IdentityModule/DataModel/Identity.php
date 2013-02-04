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
 * @Sds\Serializer(@Sds\ClassName)
 * @Sds\Rest
 * @Sds\Generator({
 *     @Sds\Dojo\Model,
 *     @Sds\Dojo\JsonRest
 * })
 */
class Identity implements CredentialInterface, IdentityInterface, RoleAwareIdentityInterface
{
    use CredentialTrait;
    use IdentityTrait;
    use RoleAwareIdentityTrait;

    /**
     * @ODM\String
     * @Sds\Validator\Required
     * @Sds\Validator\PersonalName
     * @Sds\Generator({
     *     @Sds\Dojo\Input
     * })
     */
    protected $firstname;

    /**
     * @ODM\Field(type="string")
     * @Sds\Validator\Required,
     * @Sds\Validator\PersonalName
     * @Sds\Generator({
     *     @Sds\Dojo\Input
     * })
     */
    protected $lastname;

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
     * @ODM\ReferenceOne(targetDocument="Profile", simple=true, cascade="all")
     */
    protected $profile;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = (string) $email;
    }

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

    public function getProfile() {
        return $this->profile;
    }

    public function setProfile(Profile $profile) {
        $this->profile = $profile;
    }
}
