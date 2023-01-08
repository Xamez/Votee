<?php

namespace App\Votee\Model\DataObject;

use Exception;

enum Periodes : string {

    case ECRITURE = 'Période d\'écriture';
    case TRANSITION = 'Période de transition';
    case VOTE = "Période de vote";
    case PREPARATION = "Période de préparation";
    case RESULTAT = "Période de résultat";

    public static function getFromKey($keyString) : Periodes {
        foreach (Periodes::cases() as $periode) {
            if ($periode->name === $keyString) {
                return $periode;
            }
        }
        throw new Exception("Periode '" . $keyString . "' not found");
    }

    public static function toArray(): array {
        foreach(self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }

}