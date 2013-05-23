<?php
return array(
    'sds' => [
        'identity' => [
            'shared_controller_options' => [
                'document_manager' => 'doctrine.odm.documentmanager.default',
                'document_validator' => 'doctrineextensions.default.documentvalidator',
                'serializer' => 'doctrineextensions.default.serializer',
            ],
            'identity_controller_options' => [
                'document_class' => 'Sds\IdentityModule\DataModel\Identity',
                'limit' => 30 //max number of records returned from getList
            ],
            'forgot_credential_token_controller_options' => [
                'manifest_name' => 'default',
                'document_class' => 'Sds\IdentityModule\DataModel\ForgotCredentialToken',
                'limit' => 1, //max number of records returned from getList
                'identity_class' => 'Sds\IdentityModule\DataModel\Identity',
                'mail_transport' => 'Sds\IdentityModule\MailTransport\Stmp',
                'mail_from' => 'sds@identitymodule.dummy',
                'expiry' => 4*60*60, //time in seconds
                'mail_subject' => 'recover password',
            ],
            'email' => [
                'salt' => 'qw4q35varyw456vaertwqetsvtruerraw45q3s',
                'key' => 'change this key phrase in your own app',
            ]
        ],
        'doctrineExtensions' => [
            'manifest' => [
                'default' => [
                    'extension_configs' => [
                        'extension.readonly' => true,
                        'extension.serializer' => true,
                        'extension.validator' => true,
                        'extension.crypt' => true,
                        'extension.rest' => true,
                    ]
                ]
            ]
        ],
    ],

    'controllers' => array(
        'factories' => array(
            'rest.default.forgotcredentialtoken' => 'Sds\IdentityModule\Service\ForgotCredentialTokenControllerFactory'
        ),
    ),

//    'controllers' => array(
//        'factories' => array(
//            'Sds\IdentityModule\Controller\IdentityController' => function($serviceLocator){
//                $config = $serviceLocator
//                        ->getServiceLocator()
//                        ->get('Config')['sds']['identity'];
//                return new Sds\IdentityModule\Controller\IdentityController(
//                    array_merge($config['sharedControllerOptions'], $config['identityControllerOptions'])
//                );
//            },
//            'Sds\IdentityModule\Controller\ForgotCredentialTokenController' => function($serviceLocator){
//                $config = $serviceLocator
//                        ->getServiceLocator()
//                        ->get('Config')['sds']['identity'];
//                return new Sds\IdentityModule\Controller\ForgotCredentialTokenController(
//                    array_merge($config['sharedControllerOptions'], $config['forgotCredentialTokenControllerOptions'])
//                );
//            }
//        ),
//    ),

    'doctrine' => array(
        'driver' => array(
            'default' => array(
                'drivers' => array(
                    'Sds\IdentityModule\DataModel' => 'doctrine.driver.identity'
                ),
            ),
            'identity' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../src/Sds/IdentityModule/DataModel'
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Sds\IdentityModule\MailTransport\Stmp' => function(){
                return new \Zend\Mail\Transport\Smtp([
                    //Add your mail settings here
                ]);
            },
        ),
    ),

    'view_manager' => array(
        'template_map'             => array(
            'email/recover-password' => __DIR__ . '/../view/email/recover-password.phtml',
            'sds/recover-password/recover-password' => __DIR__ . '/../view/sds/recover-password/recover-password.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    )
);
