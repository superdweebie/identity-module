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
 * @ODM\Document
 * @Sds\SerializeClassName
 * @Sds\ClassDojo(
 *     className = true
 * )
 */
class User implements RoleAwareUserInterface, AuthInterface
{
    use AuthTrait;
    use RoleAwareUserTrait;

    /**
     * @ODM\Id(strategy="UUID")
     * @Sds\PropertyDojo(
     *     inputType = "hidden"
     * )
     */
    protected $id;

    /**
     * @ODM\String
     * @Sds\Required
     * @Sds\PropertyValidators({
     *     @Sds\Validator(class = "Sds\Common\Validator\PersonalNameValidator")
     * })
     * @Sds\PropertyDojo(
     *     required = true,
     *     validators = {
     *         @Sds\DojoValidator(module = "Sds/Validator/PersonalNameValidator")
     *     }
     * )
     */
    protected $firstname;

    /**
     * @ODM\Field(type="string")
     * @Sds\Required
     * @Sds\PropertyValidators({
     *     @Sds\Validator(class = "Sds\Common\Validator\PersonalNameValidator")
     * })
     * @Sds\PropertyDojo(
     *     required = true,
     *     validators = {@Sds\DojoValidator(module = "Sds/Validator/PersonalNameValidator")}
     * )
     */
    protected $lastname;

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
}