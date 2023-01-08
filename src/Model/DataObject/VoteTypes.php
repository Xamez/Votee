<?php

namespace App\Votee\Model\DataObject;

use Exception;

enum VoteTypes : string {

    case JUGEMENT_MAJORITAIRE = 'Jugement majoritaire';
    case OUI_NON = 'Oui/Non';
    case CLASSEMENT = "Classement";

    public static function getFromKey($keyString) : VoteTypes {
        foreach (VoteTypes::cases() as $voteType) {
            if ($voteType->name === $keyString) {
                return $voteType;
            }
        }
        throw new Exception("VoteType '" . $keyString . "' not found");
    }

    public static function toArray(): array {
        foreach(self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }

}