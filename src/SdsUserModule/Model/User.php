<?php

namespace SdsUserModule\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\User\Behaviour\AuthUser;
use SdsDoctrineExtensions\Serializer\Behaviour\Serializer;
use SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser;
use SdsDoctrineExtensions\AccessControl\Behaviour\UserAccessControl;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;
use SdsCommon\AccessControl\UserInterface as CommonAccessControlUser;

/** @ODM\Document */
class User implements \JsonSerializable, CommonAccessControlUser
{
    use AuthUser, Serializer, ActiveUser, UserAccessControl;
    
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