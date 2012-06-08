<?php
return array(
    'sdsUserConfig' => array( 
        'guestUser' => array(
            'username' => 'guest',
            'roles' => array(
                array(
                    'name' => 'guest'
                ),
            ),
        ),
    ),
    
    'doctrine' => array(
        'drivers' => array(
            'odm' => array(
                array(
                    'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                    'namespace' => 'SdsUserModule\Model',
                    'paths' => array(
                        'vendor/superdweebie/SdsUserModule/src/SdsUserModule/Model'                   
                    ),
                ),
            ),
        ),
    ),
);
