<?php
return array(
    'sds' => array(
        'user' => array(
            'mailTransport' => 'sds.mailTransport.file'
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Zend\Authentication\AuthenticationService' => 'Sds\UserModule\Test\TestAsset\MockAuthenticationService',
        ),
        'factories' => array(
            'sds.mailTransport.file' => function(){
                return new \Zend\Mail\Transport\File(new \Zend\Mail\Transport\FileOptions([
                    'path' => __DIR__ . '/email/',
                    'callback' => function(){return 'test_mail.tmp';}
                ]));
            },
        ),
    ),
);