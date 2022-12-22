<?php
namespace App\Votee\Lib;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\HTTP\Session;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
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

    public static function getUtilisateurConnecte() {
        $login = self::estConnecte() ? Session::getInstance()->lire(static::$cleConnexion) : null;
        if ($login != null) {
            return (new UtilisateurRepository())->select($login);
        }
        return null;
    }

    public static function creerQuestion(): bool {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            return $utilisateur->getNbQuestRestant() > 0;
        }
        return false;
    }

    public static function creerProposition($idQuestion): bool {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            $nbPropRestant = (new QuestionRepository)->getPropRestant($idQuestion, $utilisateur->getLogin());
            return !($nbPropRestant == null) && $nbPropRestant > 0;
        }
        return false;
    }

    public static function creerFusion($idProposition): bool {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            $nbFusionRestant = (new PropositionRepository())->getFusionRestant($idProposition, $utilisateur->getLogin());
            return !($nbFusionRestant == null) && $nbFusionRestant > 0;
        }
        return false;
    }

    public static function questionValide($idQuestion): bool {
        if (self::estConnecte()) {
            $idProposition = ConnexionUtilisateur::getPropByLogin($idQuestion);
            $proposition = (new PropositionRepository())->select($idProposition);
            if ($proposition->isVisible()) return true;
        }
        return false;
    }

    public static function estAdministrateur() : bool {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->selectAdministrateur(Session::getInstance()->lire(static::$cleConnexion));
        }
        return false;
    }

    public static function getRolesQuestion($idQuestion): array {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->getRolesQuestion(Session::getInstance()->lire(static::$cleConnexion),$idQuestion);
        }
        return [];
    }

    public static function getRolesProposition($idProposition): array {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->getRolesProposition(Session::getInstance()->lire(static::$cleConnexion),$idProposition);
        }
        return [];
    }

    /** Retourne l'id de la proposition de l'utilisateur connecté dans une question donnée */
    public static function getPropByLogin($idQuestion): ?int {
        if (self::estConnecte()) {
            return (new PropositionRepository())->selectPropById($idQuestion, Session::getInstance()->lire(static::$cleConnexion));
        }
        return null;
    }
}