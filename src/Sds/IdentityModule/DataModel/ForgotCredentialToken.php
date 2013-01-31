<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\DataModel;

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
 * @Sds\Serializer(@Sds\Ignore)
 * @Sds\Rest
 * @Sds\Generator({
 *     @Sds\Dojo\Model,
 *     @Sds\Dojo\JsonRest
 * })
 */
class ForgotCredentialToken
{

    /**
     * @ODM\Id(strategy="none")
     */
    protected $code;

    /**
     * @ODM\Index(unique = true)
     * @ODM\String
     */
    protected $identityName;

    /**
     *
     * @ODM\Timestamp
     */
    protected $expires;

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function getIdentityName() {
        return $this->identityName;
    }

    public function setIdentityName($identityName) {
        $this->identityName = $identityName;
    }

    public function getExpires() {
        return $this->expires;
    }

    public function setExpires($expires) {
        $this->expires = $expires;
    }
}
