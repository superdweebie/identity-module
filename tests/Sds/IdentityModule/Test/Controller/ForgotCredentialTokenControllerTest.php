<?php

namespace Sds\IdentityModule\Test\Controller;

use Sds\Common\Crypt\Hash;
use Sds\IdentityModule\Test\TestAsset\TestData;
use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Request;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ForgotCredentialTokenControllerTest extends AbstractHttpControllerTestCase{

    protected static $staticDcumentManager;

    protected static $dbDataCreated = false;

    public static function tearDownAfterClass(){
        TestData::remove(static::$staticDcumentManager);
    }

    public function setUp(){

        $this->setApplicationConfig(
            include __DIR__ . '/../../../../test.application.config.php'
        );

        parent::setUp();

        $this->documentManager = $this->getApplicationServiceLocator()->get('doctrine.odm.documentmanager.default');
        static::$staticDcumentManager = $this->documentManager;

        if ( ! static::$dbDataCreated){
            //Create data in the db to query against
            TestData::create($this->documentManager);
            static::$dbDataCreated = true;
        }
    }

    public function testCreateTokenWithEmail(){

        $accept = new Accept;
        $accept->addMediaType('application/json');

        $this->getRequest()
            ->setMethod(Request::METHOD_POST)
            ->setContent('{"email": "toby@awesome.com"}')
            ->getHeaders()->addHeaders([$accept, ContentType::fromString('Content-type: application/json')]);

        $this->dispatch('/rest/forgotcredentialtoken');

        $response = $this->getResponse();
        $result = json_decode($response->getContent(), true);
        $this->assertFalse(isset($result));
        $this->assertResponseStatusCode(201);
        $this->assertFalse($response->getHeaders()->has('Location'));

        //check the email
        $this->assertTrue(file_exists(__DIR__ . '/../../../../email/test_mail.tmp'));
    }

    public function testChangeCredentialWithToken(){

        //complete the password recovery
        $text = file_get_contents(__DIR__ . '/../../../../email/test_mail.tmp');
        preg_match('/\/rest\/forgotcredentialtoken\/[a-zA-Z0-9]+/', $text, $match);

        $accept = new Accept;
        $accept->addMediaType('application/json');

        $this->getRequest()
            ->setMethod(Request::METHOD_PUT)
            ->setContent('{"credential": "newPassword1"}')
            ->getHeaders()->addHeaders([$accept, ContentType::fromString('Content-type: application/json')]);

        $this->dispatch($match[0]);

        $response = $this->getResponse();
        $result = json_decode($response->getContent(), true);
        $this->assertFalse(isset($result));
        $this->assertResponseStatusCode(204);

        $identity = $this->documentManager
            ->getRepository('Sds\IdentityModule\DataModel\Identity')
            ->findOneBy(['identityName' => 'toby']);

        $this->assertTrue(Hash::hashCredential($identity, 'newPassword1') == $identity->getCredential());
    }
}

