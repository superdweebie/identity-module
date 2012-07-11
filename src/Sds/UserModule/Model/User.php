<?php

namespace Sds\UserModule\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Auth\AuthInterface;
use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\Auth\Behaviour\AuthTrait;
use Sds\DoctrineExtensions\User\Behaviour\RoleAwareUserTrait;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use Sds\DoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;


/** @ODM\Document */
class User implements RoleAwareUserInterface, AuthInterface
{
    use AuthTrait;
    use RoleAwareUserTrait;

    protected $objclass = 'User';

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $firstname;

    /**
     * @ODM\Field(type="string")
     */
    protected $lastname;

    /**
     * @ODM\Field(type="string")
     */
    protected $nickname;

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

    public function getNickname() {
        return $this->nickname;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }
}