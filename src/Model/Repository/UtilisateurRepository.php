<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Utilisateur;

class UtilisateurRepository {

    protected function getNomsColonnes(): array {
        return array(
            'login',
            'motDePasse',
            'nom',
            'prenom',
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
            $utilisateurFormatTableau['login'],
            $utilisateurFormatTableau['motDePasse'],
            $utilisateurFormatTableau['nom'],
            $utilisateurFormatTableau['prenom'],
        );
    }
}