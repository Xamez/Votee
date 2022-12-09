<?php
namespace App\Votee\Lib;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\HTTP\Session;
use App\Votee\Model\Repository\UtilisateurRepository;

class ConnexionUtilisateur {
    // L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void {
        Session::getInstance()->enregistrer(static::$cleConnexion, $loginUtilisateur);
    }

    public static function estConnecte(): bool {
        return Session::getInstance()->contient(static::$cleConnexion);
    }

    public static function deconnecter(): void {
        Session::getInstance()->supprimer(static::$cleConnexion);
    }

    public static function getUtilisateurConnecte(): ?Utilisateur {
        $login = self::estConnecte() ? Session::getInstance()->lire(static::$cleConnexion) : null;
        if ($login != null) {
            $utilisateur = (new UtilisateurRepository())->select($login);
            return $utilisateur;
        }
        return null;
    }

    public static function estUtilisateur($utilisateur): bool {
        return self::estConnecte() && $utilisateur == Session::getInstance()->lire(static::$cleConnexion);
    }

//    public static function estAdministrateur() : bool {
//        if (self::estConnecte()) {
//            $utilisateur = (new UtilisateurRepository())->select(Session::getInstance()->lire(static::$cleConnexion));
//            return $utilisateur->isEstAdmin();
//        }
//        return false;
//    }
}