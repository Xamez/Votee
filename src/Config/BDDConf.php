<?php

namespace App\Votee\Config;

class BDDConf {

    static private array $databases = array(
        'hostname' => 'webinfo',
        'database' => 'cazauxl',
        'login' => 'cazauxl',
        'password' => 'NA2Lz@-m0T0v1DSk'
    );

    static public function getLogin() : string {
        return static::$databases['login'];
    }

    static public function getHostname() : string {
        return static::$databases['hostname'];
    }

    static public function getDatabase() : string {
        return static::$databases['database'];
    }

    static public function getPassword() : string {
        return static::$databases['password'];
    }
}