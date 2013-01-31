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

    use IdentityTrait;

}