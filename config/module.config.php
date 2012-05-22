<?php
return array(
    'sds_user_config' => array( 
        'drivers' => array(
            array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'namespace' => 'SdsUserModule\Model',
                'paths' => array(
                    'module/SdsUserModule/src/SdsUserModule/Model'
                ),                             
            ),         
        ),
        'guest_user' => array(
            'username' => 'guest',
            'roles' => array(
                
            ),
        ),
    ),
);
