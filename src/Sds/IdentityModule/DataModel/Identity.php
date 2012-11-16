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
 */
class Identity implements CredentialInterface, IdentityInterface, RoleAwareIdentityInterface
{
    use CredentialTrait;
    use IdentityTrait;
    use RoleAwareIdentityTrait;

    /**
     * @ODM\String
     * @Sds\RequiredValidator
     * @Sds\PersonalNameValidator
     */
    protected $firstname;

    /**
     * @ODM\Field(type="string")
     * @Sds\RequiredValidator,
     * @Sds\PersonalNameValidator
     */
    protected $lastname;

    /**
     * @ODM\String
     * @Sds\Serializer(@Sds\Ignore("down"))
     * @Sds\CryptBlockCipher(
     *     keyClass = "Sds\IdentityModule\Crypt\EmailKey"
     * )
     * @Sds\RequiredValidator,
     * @Sds\EmailAddressValidator
     */
    protected $email;

    /**
     * @ODM\EmbedOne(
     *     targetDocument="Sds\IdentityModule\DataModel\Profile"
     * )
     */
    protected $profile;

    /**
     *
     * @ODM\Timestamp
     * @Sds\Serializer(@Sds\Ignore)
     */
    protected $forgotCredentialExpires;

    /**
     *
     * @ODM\String
     * @Sds\Serializer(@Sds\Ignore("down"))
     */
    protected $forgotCredentialCode;

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

    public function getForgotCredentialExpires() {
        return $this->forgotCredentialExpires;
    }

    public function setForgotCredentialExpires($forgotCredentialExpires) {
        $this->forgotCredentialExpires = $forgotCredentialExpires;
    }

    public function getForgotCredentialCode() {
        return $this->forgotCredentialCode;
    }

    public function setForgotCredentialCode($forgotCredentialCode) {
        $this->forgotCredentialCode = $forgotCredentialCode;
    }
}
