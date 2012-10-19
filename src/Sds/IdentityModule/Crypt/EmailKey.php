<?php

namespace Sds\IdentityModule\Crypt;

use Sds\Common\Crypt\KeyInterface;

class EmailKey implements KeyInterface {

    protected static $key;

    public static function setKey($key) {
        self::$key = (string) $key;
    }

    public static function getKey() {
        return self::$key;
    }
}