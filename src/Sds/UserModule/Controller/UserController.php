<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\UserModule\Controller;

use Sds\JsonController\AbstractJsonRpcController;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UserController extends AbstractJsonRpcController
{

    protected $serializer;

    protected $validator;

    protected $documentManager;

    public function setSerializer(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function registerRpcMethods(){
        return array(
            'recoverPassword',
            'register'
        );
    }

    /**
     *
     * @param string $username
     * @param string $email
     * @return object
     */
    public function recoverPassword($username, $email)
    {

        $this->documentManager->getRepository('Sds\UserModule\DataModel\User');

        if ( ! $username == ''){

        }

        if ( ! $email != ''){

        }
    }

    /**
     *
     * @param object $newUser
     * @return object
     */
    public function register($data)
    {
        $newUser = $this->serializer->fromArray($data);
        if ( ! $this->validator->isValid($newUser)){

        }

        $this->documentManager->persist($newUser);
        $this->documentManager->flush();

        return $this->serializer->toArray($newUser);
    }
}

