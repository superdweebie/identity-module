<?php
return array(
    'sds' => [
        'identity' => [
            'sharedControllerOptions' => [
                'documentManager' => 'doctrine.odm.documentmanager.default',
                'documentValidator' => 'Sds\DoctrineExtensions\DocumentValidator',
                'serializer' => 'Sds\DoctrineExtensions\Serializer',
            ],
            'identityControllerOptions' => [
                'documentClass' => 'Sds\IdentityModule\DataModel\Identity',
                'limit' => 30 //max number of records returned from getList
            ],
            'forgotCredentialTokenControllerOptions' => [
                'documentClass' => 'Sds\IdentityModule\DataModel\ForgotCredentialToken',
                'limit' => 1, //max number of records returned from getList
                'identityClass' => 'Sds\IdentityModule\DataModel\Identity',
                'mailTransport' => 'Sds\IdentityModule\MailTransport\Stmp',
                'mailFrom' => 'sds@identitymodule.dummy',
                'expiry' => 4*60*60, //time in seconds
                'mailSubject' => 'recover password',
            ],
            'email' => [
                'salt' => 'qw4q35varyw456vaertwqetsvtruerraw45q3s',
                'key' => 'change this key phrase in your own app',
            ]
        ],
        'doctrineExtensions' => [
            'extensionConfigs' => [
                'Sds\DoctrineExtensions\Readonly' => true,
                'Sds\DoctrineExtensions\Serializer' => true,
                'Sds\DoctrineExtensions\Validator' => true,
                'Sds\DoctrineExtensions\Crypt' => true,
                'Sds\DoctrineExtensions\Rest' => [
                    'basePath' => 'http://localhost/ZendSkeletonApplication/'
                ],
                'Sds\DoctrineExtensions\Dojo' => [
                    'destPaths' => [
                        'all' => [
                            'filter' => 'Sds\IdentityModule',
                            'path' => 'vendor/dojo'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
//            'Sds\Zf2Extensions\RestRoute' => [
//                'options' => [
//                    'endpointToControllerMap' => [
//                        'identity' => 'Sds\IdentityModule\Controller\IdentityController',
//                        'forgotCredentialToken' => 'Sds\IdentityModule\Controller\ForgotCredentialTokenController'
//                    ],
//                ],
//            ],
        ],
    ],

    'controllers' => array(
        'factories' => array(
            'Sds\IdentityModule\Controller\IdentityController' => function($serviceLocator){
                $config = $serviceLocator
                        ->getServiceLocator()
                        ->get('Config')['sds']['identity'];
                return new Sds\IdentityModule\Controller\IdentityController(
                    array_merge($config['sharedControllerOptions'], $config['identityControllerOptions'])
                );
            },
            'Sds\IdentityModule\Controller\ForgotCredentialTokenController' => function($serviceLocator){
                $config = $serviceLocator
                        ->getServiceLocator()
                        ->get('Config')['sds']['identity'];
                return new Sds\IdentityModule\Controller\ForgotCredentialTokenController(
                    array_merge($config['sharedControllerOptions'], $config['forgotCredentialTokenControllerOptions'])
                );
            }
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'default' => array(
                'drivers' => array(
                    'Sds\IdentityModule\DataModel' => 'Sds\IdentityModule\DataModel'
                ),
            ),
            'Sds\IdentityModule\DataModel' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    'vendor/superdweebie/identity-module/src/Sds/IdentityModule/DataModel'
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
