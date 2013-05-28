<?php
return array(
    'sds' => [
        'identity' => [
            'identity_controller_options' => [
                'document_manager' => 'doctrine.odm.documentmanager.default',
                'document_class' => 'Sds\IdentityModule\DataModel\Identity',
                'limit' => 30 //max number of records returned from getList
            ],
            'forgot_credential_token_controller_options' => [
                'document_manager' => 'doctrine.odm.documentmanager.default',
                'endpoint'         => 'forgotcredentialtoken',
                'manifest_name'    => 'default',
                'document_class'   => 'Sds\IdentityModule\DataModel\ForgotCredentialToken',
                'identity_class'   => 'Sds\IdentityModule\DataModel\Identity',
                'mail_transport'   => 'my_mail_transport_instance',
                'mail_from'        => 'sds@identitymodule.dummy',
                'expiry'           => 4*60*60, //time in seconds
                'mail_subject'     => 'recover password',
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
                        'extension.accesscontrol' => true,
                        'extension.readonly'      => true,
                        'extension.serializer'    => true,
                        'extension.validator'     => true,
                        'extension.crypt'         => true,
                        'extension.rest'          => [
                            'endpoint_map' => [
                                'identity'              => 'Sds\IdentityModule\DataModel\Identity',
                                'forgotcredentialtoken' => 'Sds\IdentityModule\DataModel\ForgotCredentialToken'
                            ]
                        ],
                        'extension.dojo' => [
                            'persist_to_file' => false,
                            'file_paths' => [[
                                'filter' => 'Sds/IdentityClient',
                                'path' => 'public/dev-assets'
                            ]]
                        ],
                        'extension.generator' => [
                            'resource_map' => [
                                'Sds/IdentityClient/Identity/Model.js' => [
                                    'generator' => 'generator.dojo.model',
                                    'class'     => 'Sds\IdentityModule\DataModel\Identity',
                                ],
                                'Sds/IdentityClient/Identity/IdentityName/Input.js' => [
                                    'generator' => 'generator.dojo.input',
                                    'class'     => 'Sds\IdentityModule\DataModel\Identity',
                                    'options'   => [
                                        'field' => 'identityName'
                                    ]
                                ],
                                'Sds/IdentityClient/Identity/IdentityName/Validator.js' => [
                                    'generator' => 'generator.dojo.validator',
                                    'class'     => 'Sds\IdentityModule\DataModel\Identity',
                                    'options'   => [
                                        'field' => 'identityName'
                                    ]
                                ],
                                'Sds/IdentityClient/Identity/Credential/Input.js' => [
                                    'generator' => 'generator.dojo.input',
                                    'class'     => 'Sds\IdentityModule\DataModel\Identity',
                                    'options'   => [
                                        'field' => 'credential'
                                    ]
                                ],
                                'Sds/IdentityClient/Identity/Credential/Validator.js' => [
                                    'generator' => 'generator.dojo.validator',
                                    'class'     => 'Sds\IdentityModule\DataModel\Identity',
                                    'options'   => [
                                        'field' => 'credential'
                                    ]
                                ],
                            ]
                        ]
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
