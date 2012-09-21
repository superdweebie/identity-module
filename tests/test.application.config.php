<?php
return array(
    'modules' => array(
        'Sds\UserModule',
        'DoctrineModule',
        'DoctrineMongoODMModule',
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