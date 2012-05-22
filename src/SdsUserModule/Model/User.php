<?php

namespace SdsUserModule\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\User\Behaviour\AuthUser,
    SdsDoctrineExtensions\Serializer\Behaviour\Serializer,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser,
    SdsDoctrineExtensions\AccessControl\Behaviour\UserAccessControl,    
    SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly,
    SdsDoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;

/** @ODM\Document */
class User implements \JsonSerializable
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