<?php

namespace Sds\IdentityModule\Test\TestAsset;

use Sds\IdentityModule\DataModel\Identity;

class MockAuthenticationService {

    public function getIdentity(){
        $identity = new Identity;
        $identity->setIdentityName('toby');
        return $identity;
    }
}
