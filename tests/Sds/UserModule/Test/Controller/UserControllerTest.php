<?php

namespace Sds\UserModule\Test\Controller;

use Sds\ModuleUnitTester\AbstractControllerTest;
use Zend\Http\Request;

class UserControllerTest extends AbstractControllerTest{

    protected $serviceMapArray;

    public function setUp(){

        $this->controllerName = 'sds.user';

        parent::setUp();
    }

    public function testRegister(){
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"method": "login", "params": ["toby", "wrong password"], "id": 1}');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertEquals('Sds\AuthModule\Exception\LoginFailedException', $returnArray['error']['type']);
    }

    public function testLoginSuccess(){
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"method": "login", "params": ["toby", "password"], "id": 1}');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertEquals('toby', $returnArray['result']['user']['username']);
    }

    public function testAlreadyLoggedIn(){
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"method": "login", "params": ["toby", "password"], "id": 1}');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals(1, $returnArray['id']);
        $this->assertEquals('Sds\AuthModule\Exception\AlreadyLoggedInException', $returnArray['error']['type']);
    }
}

