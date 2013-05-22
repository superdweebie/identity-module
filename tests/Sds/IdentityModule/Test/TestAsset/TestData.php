<?php

namespace Sds\IdentityModule\Test\TestAsset;

use Sds\IdentityModule\DataModel\Identity;

class TestData{

    public static function create($documentManager){

        //Create data in the db to query against
        $documentManager->getConnection()->selectDatabase('identityModuleTest');

        $identity = new Identity;
        $identity->setIdentityName('toby');
        $identity->setFirstName('Toby');
        $identity->setLastName('Awesome');
        $identity->setCredential('password1');
        $identity->setEmail('toby@awesome.com');
        $documentManager->persist($identity);
        $documentManager->flush();
        $documentManager->clear();
    }

    public static function remove($documentManager){
        //Cleanup db after all tests have run
        $collections = $documentManager->getConnection()->selectDatabase('identityModuleTest')->listCollections();
        foreach ($collections as $collection) {
            $collection->remove(array(), array('safe' => true));
        }
    }
}