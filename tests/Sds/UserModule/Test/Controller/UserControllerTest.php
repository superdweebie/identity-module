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

        //clear any residual test data first
        $this->collection = $this->documentManager->getDocumentCollection($this->controller->getUserClass());
        $this->collection->remove(array('username' => 'toby'));
    }

    public function tearDown(){
        $this->collection->remove(array('username' => 'toby'));
    }

    public function testUsernameAvailable(){

        $documentManager = $this->documentManager;

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"method": "usernameAvailable", "params": ["toby"], "id": 1}');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertEquals(true, $returnArray['result']);

        //create the moock user
        $user = new User;
        $user->setUsername('toby');
        $documentManager->persist($user);
        $documentManager->flush();
        $documentManager->clear();

        //check for fail
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertFalse($returnArray['result']);
    }

    public function testRegister(){

        $documentManager = $this->documentManager;

        //use controller
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "method": "register",
            "params": [
                {
                    "_className":"Sds\\\\UserModule\\\\DataModel\\\\User",
                    "firstname":"Toby",
                    "lastname":"Awesome",
                    "email":"toby@awesome.com",
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
        $this->assertNotEquals('password1', $user->getPassword());
        $this->assertNotEquals('toby@awesome.com', $user->getEmail());
    }

    public function testInvalidRegister(){

        //use controller
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "method": "register",
            "params": [
                {
                    "_className":"Sds\\\\UserModule\\\\DataModel\\\\User",
                    "firstname":"Toby",
                    "lastname":"Awesome",
                    "email":"toby@awesome.com",
                    "username":"toby"
                }
            ],
            "id": 1
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertTrue(isset($returnArray['error']));
        $this->assertEquals('Sds\UserModule\Exception\InvalidArgumentException', $returnArray['error']['type']);
        $this->assertEquals('Field "password": This value is required.', $returnArray['error']['message']);
    }

    public function testRecoverPasswordByUsername(){

        $documentManager = $this->documentManager;

        //create the mock user
        $user = new User;
        $user->setUsername('toby');
        $user->setEmail('toby@awesome.com');
        $user->setPassword('password1');
        $documentManager->persist($user);
        $documentManager->flush();
        $documentManager->clear();

        //use controller
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "method": "recoverPassword",
            "params": ["toby"],
            "id": 1
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertTrue($returnArray['result']);

        //check the email
        $this->assertTrue(file_exists(__DIR__ . '/../../../email/test_mail.tmp'));

        //complete the password recovery


    }
}

