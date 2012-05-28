<?php
return array(
    'sds_user_config' => array( 
        'drivers' => array(
            array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'namespace' => 'SdsUserModule\Model',
                'paths' => array(
                    'vendor/superdweebie/SdsUserModule/src/SdsUserModule/Model'                   
                ),                             
            ),         
        ),
        'guestUser' => array(
            'username' => 'guest',
            'roles' => array(
                array(
                    'name' => 'guest'
                ),
            ),
        ),
    ),
);
