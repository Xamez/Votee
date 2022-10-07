<?php

namespace App\Votee\Model;

use App\Votee\Config\BDDConf;
use PDO;

class Model {
    private static ?Model $instance = null;

    private PDO $pdo;

    public static function getPdo(): PDO {
        return static::getInstance()->pdo;
    }

    public function __construct() {
        $hostname = BDDConf::getHostname();
        $databaseName = BDDConf::getDatabase();
        $login = BDDConf::getLogin();
        $password = BDDConf::getPassword();

        //  Connexion base de donnée
        $this->pdo = new PDO("mysql:host=$hostname;dbname=$databaseName", $login, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private static function getInstance(): Model {
        if (is_null(static::$instance))
            static::$instance = new Model();
        return static::$instance;
    }
}