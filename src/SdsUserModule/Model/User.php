<?php

namespace SdsUserModule\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\User\Behaviour\AuthUserTrait;
use SdsDoctrineExtensions\Serializer\Behaviour\SerializerTrait;
use SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUserTrait;
use SdsDoctrineExtensions\AccessControl\Behaviour\RoleAwareUserTrait;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\DoNotSerialize as SDS_DoNotSerialize;
use SdsCommon\User\AuthUserInterface;
use SdsCommon\AccessControl\RoleAwareUserInterface;

/** @ODM\Document */
class User 
implements 
    \JsonSerializable, 
    AuthUserInterface, 
    RoleAwareUserInterface
{
    use AuthUserTrait;
    use SerializerTrait;
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