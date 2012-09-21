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
 * @ODM\Document
 * @Sds\Serializer(@Sds\ClassName)
 * @Sds\Dojo(@Sds\ClassName)
 */
class User implements RoleAwareUserInterface, AuthInterface
{
    use AuthTrait;
    use RoleAwareUserTrait;

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
     * @Sds\Required
     * @Sds\ValidatorGroup(
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
     * @Sds\Required
     * @Sds\ValidatorGroup(
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
     * @ODM\EmbedOne(
     *     targetDocument="Sds\UserModule\DataModels\Profile"
     * )
     */
    protected $profile;

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
}