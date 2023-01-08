<?php

namespace App\Votee\Model\HTTP;
use Exception;

class Session {
    private static ?Session $instance = null;

    private function __construct() {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    public static function getInstance(): Session {
        if (is_null(static::$instance))
            static::$instance = new Session();
        return static::$instance;
    }

    public function contient($name): bool {
        return isset($_SESSION[$name]);
    }

    public function enregistrer(string $name, mixed $value): void {
        $_SESSION[$name] = $value;
    }

    public function lire(string $name): mixed {
        return $_SESSION[$name];
    }

    public function supprimer($name): void {
        unset($_SESSION[$name]);
    }
}