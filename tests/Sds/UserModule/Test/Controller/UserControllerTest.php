<?php

namespace Sds\UserModule\Test\Controller;

use Sds\ModuleUnitTester\AbstractControllerTest;
use Sds\UserModule\DataModel\User;
use Zend\Http\Request;

class UserControllerTest extends AbstractControllerTest{

    protected $serviceMapArray;

    protected $documentManager;

    public function setUp(){

        $this->controllerName = 'sds.user';

        parent::setUp();

        $this->documentManager = $this->serviceManager->get('doctrine.documentmanager.odm_default');
    }

    public function testUsernameAvailable(){

        $documentManager = $this->documentManager;

        //clear data first
        $collection = $documentManager->getDocumentCollection($this->controller->getUserClass());
        $collection->remove(array('username' => 'toby'));

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"method": "usernameAvailable", "params": ["toby"], "id": 1}');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertEquals(true, $returnArray['result']);

        //create the user
        $user = new User;
        $user->setUsername('toby');
        $documentManager->persist($user);
        $documentManager->flush();
        $documentManager->clear();

        //check for fail
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertEquals(false, $returnArray['result']);

        $collection->remove(array('username' => 'toby'));
    }

//    public function testRegister(){
//        $this->request->setMethod(Request::METHOD_POST);
//        $this->request->setContent('{"method": "login", "params": ["toby", "wrong password"], "id": 1}');
//        $result = $this->controller->dispatch($this->request, $this->response);
//        $returnArray = $result->getVariables();
//
//        $this->assertEquals(1, $returnArray['id']);
//        $this->assertEquals('Sds\AuthModule\Exception\LoginFailedException', $returnArray['error']['type']);
//    }

}

