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

    public static function getUtilisateurConnecte(): ?Utilisateur {
        $login = self::estConnecte() ? Session::getInstance()->lire(static::$cleConnexion) : null;
        if ($login != null) {
            return (new UtilisateurRepository())->select($login);
        }
        return null;
    }

    public static function estUtilisateur($utilisateur): bool {
        return self::estConnecte() && $utilisateur == Session::getInstance()->lire(static::$cleConnexion);
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

    public static function estAdministrateur() : bool {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->selectAdministrateur(Session::getInstance()->lire(static::$cleConnexion));
        }
        return false;
    }

    public static function estOrganisateur($idQuestion): bool {
        return self::getRoleQuestion($idQuestion) == "organisateur";
    }

    public static function estRepresentant($idProposition): bool {
        return self::getRoleProposition($idProposition) == "representant";
    }

    public static function getRoleQuestion($idQuestion): ?string {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->getRoleQuestion(Session::getInstance()->lire(static::$cleConnexion),$idQuestion);
        }
        return null;
    }

    public static function getRoleProposition($idProposition): ?string {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->getRoleProposition(Session::getInstance()->lire(static::$cleConnexion),$idProposition);
        }
        return null;
    }

    /** Retourne l'id de la proposition de l'utilisateur connecté dans une question donnée */
    public static function getPropByLogin($idQuestion): ?int {
        if (self::estConnecte()) {
            return (new PropositionRepository())->selectPropById($idQuestion, Session::getInstance()->lire(static::$cleConnexion));
        }
        return null;
    }
}