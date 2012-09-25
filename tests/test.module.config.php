<?php
return array(
    'sds' => array(
        'doctrineExtensions' => array(
            'activeUser' => 'testActiveUser',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'testActiveUser' => 'Sds\DoctrineExtensionsModule\Test\TestAsset\ActiveUser'
        ),
    ),
);