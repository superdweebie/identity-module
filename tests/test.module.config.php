<?php
return [
    'sds' => [
        'identity' => [
            'forgot_credential_token_controller_options' => [
                'mail_transport' => 'Sds\IdentityModule\MailTransport\File'
            ],
        ],
    ],
    'doctrine' => array(
        'odm' => [
            'configuration' => array(
                'default' => array(
                    'default_db'   => 'identityModuleTest',
                    'proxy_dir'    => __DIR__ . '/Proxy',
                    'hydrator_dir' => __DIR__ . '/Hydrator',
                )
            ),
        ]
    ),

//    'view_manager' => [
//        'base_path' => 'http://testpath.com'
//    ],

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
    ),

    'service_manager' => array(
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