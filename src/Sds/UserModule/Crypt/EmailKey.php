<?php

namespace Sds\UserModule\Crypt;

use Sds\Common\Crypt\KeyInterface;

class EmailKey implements KeyInterface {

    public static function getKey() {

        return 'change this key phrase in your own app';
    }
}