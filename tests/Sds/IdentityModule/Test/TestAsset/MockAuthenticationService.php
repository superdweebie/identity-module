<?php

namespace Sds\UserModule\Test\TestAsset;

use Sds\UserModule\DataModel\User;

class MockAuthenticationService {

    public function getIdentity(){
        $user = new User;
        $user->setName('toby');
        return $user;
    }
}
