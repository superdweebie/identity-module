<?php

namespace Sds\UserModule\Test\Model;

use Sds\UserModule\Model\User;
use Sds\ModuleUnitTester\AbstractTest;

class ControllerTest extends AbstractTest{

    public $controller;
    public $serviceMapArray;
    public $event;
    public $request;
    public $response;

    public function setUp(){
        parent::setUp();
    }

    protected function alterConfig(array $config) {
        return $config;
    }

    public function testUserPersist(){

        $user = new User();
        $user->setUsername('lizzz');
        $user->setFirstname('Liz');
        $user->setLastname('Jane');

        $documentManager = $this->serviceManager->get('doctrine.documentmanager.odm_default');

        $documentManager->persist($user);
        $documentManager->flush();
        $id = $user->getId();
        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($user));
        $user = $repository->find($id);

        $this->assertEquals('lizzz', $user->getUsername());
        $this->assertEquals('Liz', $user->getFirstname());
        $this->assertEquals('Jane', $user->getLastname());
    }
}

