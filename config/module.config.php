<?php
return array(
    'sds' => array(
        'user' => array(
            'enableAccessControl' => false,
        ),
        'doctrineExtensions' => array(
            'extensionConfigs' => array(
                'Sds\DoctrineExtensions\Readonly' => null,
                'Sds\DoctrineExtensions\Serializer' => null,
            ),
        ),
        'accessControl' => array(
            'controllers' => array(
                'user' => array(
                    'actions' => array(
                        'recoverPassword' => array(
                            'roles' => array(
                                \Sds\Common\AccessControl\Constant\Role::guest
                            ),
                        ),
                    ),
                ),
                'userRest' => array(
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
            'user' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        'controller' => 'user',
                    ),
                ),
            ),
            'userRest' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user/rest',
                    'defaults' => array(
                        'controller' => 'userRest',
                    ),
                ),
            ),
        ),
    ),

    'controller' => array(
        'factories' => array(
            'user' => 'Sds\UserModule\Service\UserControllerFactory',
            'userRest' => 'Sds\UserModule\Service\UserRestControllerFactory'
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'odm_default' => array(
                'drivers' => array(
                    'Sds\UserModule\Model' => 'sds.user'
                ),
            ),
            'sds.user' => array(
                'paths' => array(
                    'vendor/superdweebie/userModule/src/Sds/UserModule/Model'
                ),
            ),
        ),
    ),
);
