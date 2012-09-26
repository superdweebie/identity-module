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

    public function testRegister(){

        $documentManager = $this->documentManager;

        //clear data first
        $collection = $documentManager->getDocumentCollection($this->controller->getUserClass());
        $collection->remove(array('username' => 'toby'));

        //use controller
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "method": "register",
            "params": [
                {
                    "_className":"Sds\\\\UserModule\\\\DataModel\\\\User",
                    "firstname":"Toby",
                    "lastname":"Awesome",
                    "profile":{
                        "_className":"Sds\\\\UserModule\\\\DataModel\\\\Profile",
                        "email":"toby@awesome.com"
                    },
                    "password":"password1",
                    "username":"toby"
                }
            ],
            "id": 1
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertTrue(isset($returnArray['result']['id']));

        //load the user
        $documentManager->clear();
        $repository = $documentManager->getRepository($this->controller->getUserClass());
        $user = $repository->findOneBy(['username' => 'toby']);

        $this->assertTrue(isset($user));
        $this->assertEquals('Awesome', $user->getLastname());

        //cleanup
        $documentManager->remove($user);
        $documentManager->flush();
    }

    public function testInvalidRegister(){

        $documentManager = $this->documentManager;

        //clear data first
        $collection = $documentManager->getDocumentCollection($this->controller->getUserClass());
        $collection->remove(array('username' => 'toby'));

        //use controller
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "method": "register",
            "params": [
                {
                    "_className":"Sds\\\\UserModule\\\\DataModel\\\\User",
                    "firstname":"Toby",
                    "lastname":"Awesome",
                    "profile":{
                        "_className":"Sds\\\\UserModule\\\\DataModel\\\\Profile",
                        "email":"toby@awesome.com"
                    },
                    "password":"password1",
                    "username":"toby"
                }
            ],
            "id": 1
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertTrue(isset($returnArray['result']['id']));

        //load the user
        $documentManager->clear();
        $repository = $documentManager->getRepository($this->controller->getUserClass());
        $user = $repository->findOneBy(['username' => 'toby']);

        $this->assertTrue(isset($user));
        $this->assertEquals('Awesome', $user->getLastname());

        //cleanup
        //$documentManager->remove($user);
        //$documentManager->flush();
    }
}

