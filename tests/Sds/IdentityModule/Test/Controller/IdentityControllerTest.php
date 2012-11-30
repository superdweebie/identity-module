<?php

namespace Sds\IdentityModule\Test\Controller;

use Sds\DoctrineExtensions\Crypt\BlockCipherService;
use Sds\IdentityModule\DataModel\Identity;
use Sds\ModuleUnitTester\AbstractControllerTest;
use Zend\Http\Request;

class IdentityControllerTest extends AbstractControllerTest{

    protected $serviceMapArray;

    protected $documentManager;

    protected static $staticDcumentManager;

    protected static $dbIdentityCreated = false;

    public static function tearDownAfterClass(){
        //Cleanup db after all tests have run
        $collections = static::$staticDcumentManager->getConnection()->selectDatabase('identityModuleTest')->listCollections();
        foreach ($collections as $collection) {
            $collection->remove(array(), array('safe' => true));
        }
    }

    public function setUp(){

        $this->controllerName = 'Sds\IdentityModule\Controller\IdentityController';

        parent::setUp();

        $this->documentManager = $this->serviceManager->get('doctrine.documentmanager.odm_default');
        static::$staticDcumentManager = $this->documentManager;

        if ( ! static::$dbIdentityCreated){
            //create the mock identity
            $documentManager = $this->documentManager;
            $identity = new Identity;
            $identity->setIdentityName('toby');
            $documentManager->persist($identity);
            $documentManager->flush();
            $documentManager->clear();

            static::$dbIdentityCreated = true;
        }

    }

    public function testGetFail(){

        $this->setExpectedException('Sds\DoctrineExtensionsModule\Exception\DocumentNotFoundException');

        $this->request->setMethod(Request::METHOD_GET);
        $this->routeMatch->setParam('id', 'notToby');
        $this->controller->dispatch($this->request, $this->response);
    }

    public function testGetSucceed(){

        $this->request->setMethod(Request::METHOD_GET);
        $this->routeMatch->setParam('id', 'toby');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();

        $this->assertEquals('toby', $returnArray['identityName']);
    }

    public function testRegisterFail(){

        $this->setExpectedException('Sds\DoctrineExtensionsModule\Exception\InvalidArgumentException');

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "identityName": "lucy",
            "firstname":"Lucy",
            "lastname":"Awesome",
            "email":"invalid email",
            "credential":"invalid password"
        }');
        $this->controller->dispatch($this->request, $this->response);
    }

    public function testRegisterSucceed(){

        $documentManager = $this->documentManager;

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{
            "identityName": "lucy",
            "firstname":"Lucy",
            "lastname":"Awesome",
            "email":"lucy@awesome.com",
            "credential":"password1"
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();
        $this->assertEquals('lucy', $returnArray['identityName']);

        //load the identity from db
        $documentManager->clear();
        $repository = $documentManager->getRepository($this->controller->getOptions()->getDocumentClass());
        $identity = $repository->findOneBy(['identityName' => 'lucy']);

        $this->assertTrue(isset($identity));
        $this->assertEquals('Awesome', $identity->getLastname());
        $this->assertNotEquals('password1', $identity->getCredential());
        $this->assertNotEquals('lucy@awesome.com', $identity->getEmail());

        //check that the email can be retireved
        BlockCipherService::decryptDocument($identity, $documentManager->getClassMetadata($this->controller->getOptions()->getDocumentClass()));
        $this->assertEquals('lucy@awesome.com', $identity->getEmail());
    }

    public function testUpdateIdentity(){

        $documentManager = $this->documentManager;

        $this->routeMatch->setParam('id', 'lucy');
        $this->request->setMethod(Request::METHOD_PUT);
        $this->request->setContent('{
            "lastname":"Smiles",
            "email":"lucy@smiles.com"
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $returnArray = $result->getVariables();
        $this->assertEquals('lucy', $returnArray['identityName']);
        $this->assertEquals('Smiles', $returnArray['lastname']);

        //load the identity from db
        $documentManager->clear();
        $repository = $documentManager->getRepository($this->controller->getOptions()->getDocumentClass());
        $identity = $repository->findOneBy(['identityName' => 'lucy']);

        $this->assertTrue(isset($identity));
        $this->assertEquals('Smiles', $identity->getLastname());
        $this->assertEquals('Lucy', $identity->getFirstname());
        $this->assertNotEquals('lucy@smiles.com', $identity->getEmail());

        //check that the email can be retireved
        BlockCipherService::decryptDocument($identity, $documentManager->getClassMetadata($this->controller->getOptions()->getDocumentClass()));
        $this->assertEquals('lucy@smiles.com', $identity->getEmail());
    }

    public function testUpdateIdentityCredentialFail(){

        $this->setExpectedException('Sds\IdentityModule\Exception\InvalidArgumentException');

        $this->routeMatch->setParam('id', 'lucy');
        $this->request->setMethod(Request::METHOD_PUT);
        $this->request->setContent('{
            "credential":"password2"
        }');
        $result = $this->controller->dispatch($this->request, $this->response);
        $result->getVariables();
    }
}

