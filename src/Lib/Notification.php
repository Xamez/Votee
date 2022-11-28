<?php

namespace App\Votee\Lib;
use App\Votee\Model\HTTP\Session;

class Notification {

    private static string $cleNotif = "_notifications";

    public static function ajouter(string $type, string $message): void {
        if(Session::getInstance()->contient(static::$cleNotif)) {
            $messages = Session::getInstance()->lire(static::$cleNotif);
        }
        $messages[$type][] = $message;
        Session::getInstance()->enregistrer(static::$cleNotif, $messages);
    }

    public static function contientMessage(string $type): bool {
        if(Session::getInstance()->contient(static::$cleNotif)) {
            return isset(Session::getInstance()->lire(static::$cleNotif)[$type]) && sizeof(Session::getInstance()->lire(static::$cleNotif)[$type]) > 0;
        } else {
            return false;
        }

    }

    public static function lireMessages(string $type): array {

        $messagesType = [];
        if (self::contientMessage($type)) {
            $messages = Session::getInstance()->lire(static::$cleNotif);
            $messagesType = $messages[$type];
            $messages[$type] = [];
            Session::getInstance()->enregistrer(static::$cleNotif, $messages);
        }
        return $messagesType;
    }

    public static function lireTousMessages() : array {
        return Session::getInstance()->lire(static::$cleNotif);
    }
}