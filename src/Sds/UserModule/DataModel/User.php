<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\DataModel;

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
 * @Sds\Dojo(@Sds\ClassName)
 */
class User implements CredentialInterface, IdentityInterface, RoleAwareIdentityInterface
{
    use CredentialTrait;
    use IdentityTrait;
    use RoleAwareIdentityTrait;

    /**
     * @ODM\Id(strategy="UUID")
     * @Sds\Dojo(
     *     @Sds\Metadata({
     *         "inputType" = "hidden"
     *     })
     * )
     */
    protected $id;

    /**
     * @ODM\String
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
     *     @Sds\Validator(class = "Sds\Common\Validator\PersonalNameValidator")
     * )
     * @Sds\Dojo(
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/PersonalNameValidator")
     *     )
     * )
     */
    protected $firstname;

    /**
     * @ODM\Field(type="string")
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
     *     @Sds\Validator(class = "Sds\Common\Validator\PersonalNameValidator")
     * )
     * @Sds\Dojo(
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/PersonalNameValidator")
     *     )
     * )
     */
    protected $lastname;

    /**
     * @ODM\String
     * @Sds\Serializer(@Sds\Ignore)
     * @Sds\CryptBlockCipher(
     *     keyClass = "Sds\UserModule\Crypt\EmailKey"
     * )
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
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

    /**
     * @ODM\EmbedOne(
     *     targetDocument="Sds\UserModule\DataModel\Profile"
     * )
     */
    protected $profile;

    /**
     *
     * @ODM\Timestamp
     * @Sds\Serializer(@Sds\Ignore)
     * @Sds\Dojo(@Sds\Ignore)
     */
    protected $passwordRecoveryExpires;

    /**
     *
     * @ODM\String
     * @Sds\Serializer(@Sds\Ignore)
     */
    protected $passwordRecoveryCode;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = (string) $email;
    }

    public function getId() {
        return $this->id;
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

    public function getPasswordRecoveryExpires() {
        return $this->passwordRecoveryExpires;
    }

    public function setPasswordRecoveryExpires($passwordRecoveryExpires) {
        $this->passwordRecoveryExpires = $passwordRecoveryExpires;
    }

    public function getPasswordRecoveryCode() {
        return $this->passwordRecoveryCode;
    }

    public function setPasswordRecoveryCode($passwordRecoveryCode) {
        $this->passwordRecoveryCode = $passwordRecoveryCode;
    }
}
