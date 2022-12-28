<?php

namespace App\Votee\Config;

class Conf {

    static private array $databases = array(

        'database' => '(DESCRIPTION = 
                       (ADDRESS = (PROTOCOL = TCP)(HOST = 162.38.222.149)(PORT = 1521)) 
                       (CONNECT_DATA = 
                            (SERVICE_NAME = IUT) 
                            (SID = ORCL)))',
        'utilisateur' => 'cazauxl',
        'password' => '081975268be'
    );

    static public function getLogin() : string {
        return static::$databases['utilisateur'];
    }

    static public function getDatabase() : string {
        return static::$databases['database'];
    }

    static public function getPassword() : string {
        return static::$databases['password'];
    }
}