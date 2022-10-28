<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'LOGIN',
            'NOM',
            'PRENOM',
        );
    }

    function getNomTable(): string {
        return "Utilisateurs";
    }

    function getNomClePrimaire(): string {
        return "login";
    }

    function getProcedureInsert(): string {
        return "";
    }

    public function construire(array $utilisateurFormatTableau) : Utilisateur {
        return new Utilisateur(
            $utilisateurFormatTableau['LOGIN'],
            $utilisateurFormatTableau['NOM'],
            $utilisateurFormatTableau['PRENOM'],
        );
    }
}