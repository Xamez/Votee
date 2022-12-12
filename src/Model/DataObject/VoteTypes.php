<?php

namespace App\Votee\Model\DataObject;

enum VoteTypes: string {

    case JUGEMENT_MAJORITAIRE = 'Jugement majoritaire';
    case OUI_NON = 'Oui/Non';

    public static function toArray(): array
    {
        foreach(self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }

}