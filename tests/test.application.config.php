<?php
return array(
    'modules' => array(
        'Sds\ExceptionModule',
        'DoctrineModule',
        'DoctrineMongoODMModule',
        'Sds\DoctrineExtensionsModule',
        'Sds\IdentityModule',
        'Sds\AuthenticationModule'
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            __DIR__ . '/test.module.config.php',
        ),
        'module_paths' => array(
            './vendor',
        ),
    ),
);