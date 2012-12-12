<?php
return [
    'sds' => [
        'identity' => [
            'forgotCredentialTokenControllerOptions' => [
                'mailTransport' => 'Sds\IdentityModule\MailTransport\File'
            ],
        ],
    ],
    'doctrine' => array(
        'configuration' => array(
            'odm_default' => array(
                'default_db'   => 'identityModuleTest',
                'proxy_dir'    => __DIR__ . '/Proxy',
                'hydrator_dir' => __DIR__ . '/Hydrator',
            )
        ),
    ),
    'view_manager' => [
        'base_path' => 'http://testpath.com'
    ],
    'service_manager' => array(
        'invokables' => array(
            'Zend\Authentication\AuthenticationService' => 'Sds\IdentityModule\Test\TestAsset\MockAuthenticationService',
        ),
        'factories' => array(
            'Sds\IdentityModule\MailTransport\File' => function(){
                return new \Zend\Mail\Transport\File(new \Zend\Mail\Transport\FileOptions([
                    'path' => __DIR__ . '/email',
                    'callback' => function(){return 'test_mail.tmp';}
                ]));
            },
        ),
    ),
];