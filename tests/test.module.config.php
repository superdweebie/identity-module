<?php
return array(
    'sds' => array(
        'doctrineExtensions' => array(
            'activeUser' => 'testActiveUser',
        ),
        'user' => array(
            'mailTransport' => 'sds.mailTransport.file'
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'testActiveUser' => 'Sds\DoctrineExtensionsModule\Test\TestAsset\ActiveUser'
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