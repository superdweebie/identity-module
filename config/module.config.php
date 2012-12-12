<?php
return array(
    'sds' => array(
        'identity' => array(
            'enableAccessControl' => false,
            'sharedControllerOptions' => [
                'documentManager' => 'doctrine.documentmanager.odm_default',
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
        ),
        'doctrineExtensions' => array(
            'extensionConfigs' => array(
                'Sds\DoctrineExtensions\Readonly' => null,
                'Sds\DoctrineExtensions\Serializer' => null,
                'Sds\DoctrineExtensions\Validator' => ['validateOnFlush' => false],
                'Sds\DoctrineExtensions\Crypt' => null,
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
            ),
        ),
        'accessControl' => array(
            'controllers' => array(
                'identity' => array(
                    'actions' => array(
                        'forgotCredentialPart1' => array(
                            'roles' => array(
                                \Sds\Common\AccessControl\Constant\Role::guest
                            ),
                        ),
                    ),
                ),
                'identityRest' => array(
                    'actions' => array(
                        'create' => array(
                            'roles' => array(
                                \Sds\Common\AccessControl\Constant\Role::guest
                            ),
                        ),
                        'read' => array(
                            'roles' => array(
                                \Sds\Common\AccessControl\Constant\Role::guest
                            ),
                        ),
                        'update' => array(
                            'roles' => array(
                                \Sds\Common\AccessControl\Constant\Role::guest
                            ),
                        ),
                        'delete' => array(
                            'roles' => array(
                                \Sds\Common\AccessControl\Constant\Role::guest
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'Sds\Identity' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/identity',
                    'defaults' => array(
                        'controller' => 'Sds\IdentityModule\Controller\IdentityController',
                    ),
                ),
            ),
            'Sds\ForgotCredential' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/forgotCredential',
                    'defaults' => array(
                        'controller' => 'Sds\IdentityModule\Controller\ForgotCredentialTokenController',
                    ),
                ),
            ),
        ),
    ),

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
            'odm_default' => array(
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
