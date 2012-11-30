<?php

namespace Sds\IdentityModule\Crypt;

use Sds\Common\Crypt\KeyInterface;
use Sds\Common\Crypt\SaltInterface;

class Email implements KeyInterface, SaltInterface {

    protected static $key;

    protected static $salt;

    public static function setKey($key) {
        self::$key = (string) $key;
    }

    public static function getKey() {
        return self::$key;
    }

    public static function setSalt($salt) {
        self::$salt = (string) $salt;
    }

    public static function getSalt() {
        return self::$salt;
    }
}