<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\DataModel;

use Sds\DoctrineExtensions\Identity\DataModel\IdentityTrait;

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
class Profile
{

    /**
     *
     * @ODM\Id(strategy = "UUID")
     */
    protected $id;

    /**
     * @ODM\ReferenceOne(
     *     targetDocument="Identity",
     *     inversedBy="profile",
     *     simple=true
     * )
     */
    protected $identityName;

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

    public function getId() {
        return $this->id;
    }

    public function getIdentityName() {
        return $this->identityName;
    }

    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
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