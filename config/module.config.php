<?php
return array(
    'sdsUserConfig' => array( 
        'defaultUser' => array(
            'username' => 'default',
            'roles' => array(
                array(
                    'name' => 'default'
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
