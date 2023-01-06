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

    /** Utilisateur connecté */
    public static function getUtilisateurConnecte() {
        $login = self::estConnecte() ? Session::getInstance()->lire(static::$cleConnexion) : null;
        if ($login != null) {
            return (new UtilisateurRepository())->select($login);
        }
        return null;
    }

    /** True si utilisateur connecté peut créer une question */
    public static function creerQuestion(): bool {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            return $utilisateur->getNbQuestRestant() > 0;
        }
        return false;
    }

    /** True si l'utilisateur connecté peut créer une proposition */
    public static function creerProposition($idQuestion): bool {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            $nbPropRestant = (new QuestionRepository)->getPropRestant($idQuestion, $utilisateur->getLogin());
            return !($nbPropRestant == null) && $nbPropRestant > 0;
        }
        return false;
    }

    /** True si l'utilisateur connecté peut créer une fusion */
    public static function creerFusion($idProposition): bool {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            $nbFusionRestant = (new PropositionRepository())->getFusionRestant($idProposition, $utilisateur->getLogin());
            return !($nbFusionRestant == null) && $nbFusionRestant > 0;
        }
        return false;
    }

    /** True si l'utilisateur connecté possède une proposition visible pour la question donnée */
    public static function hasPropositionVisible($idQuestion): bool {
        if (self::estConnecte()) {
            $idProposition = ConnexionUtilisateur::getPropByLoginVisible($idQuestion);
            if ($idProposition) return true;
            else return false;
        }
        return false;
    }

    /** True si l'utilisateur connecté est un administrateur */
    public static function estAdministrateur() : bool {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->selectAdministrateur(Session::getInstance()->lire(static::$cleConnexion));
        }
        return false;
    }

    public static function estLoginAdministrateur($login) : bool {
        return (new UtilisateurRepository())->selectAdministrateur($login);
    }

    /** Ensemble des roles de l'utilisateur connecté sur la question donnée
     * parmis les roles suivants : "Organisateur", "Responsable", "CoAuteur", "Votant"
     */
    public static function getRolesQuestion($idQuestion): array {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->getRolesQuestion(Session::getInstance()->lire(static::$cleConnexion),$idQuestion);
        }
        return [];
    }

    /** Ensemble des roles de l'utilisateur connecté sur la proposition donnée
     * parmis les roles suivants : "Responsable", "CoAuteur"
     */
    public static function getRolesProposition($idProposition): array {
        if (self::estConnecte()) {
            return (new UtilisateurRepository())->getRolesProposition(Session::getInstance()->lire(static::$cleConnexion),$idProposition);
        }
        return [];
    }

    /** Les id des propositions de l'utilisateur connecté pour lesquels il est responsable dans une question donnée */
    public static function getPropByLogin($idQuestion): array {
        if (self::estConnecte()) {
            return (new PropositionRepository())->selectPropById($idQuestion, Session::getInstance()->lire(static::$cleConnexion));
        }
        return [];
    }

    /** L'id de la proposition visible de l'utilisateur connecté pour laquelle il est responsable pour une question donnée */
    public static function getPropByLoginVisible($idQuestion): ?int {
        if (self::estConnecte()) {
            $idPropositions = self::getPropByLogin($idQuestion);
            foreach ($idPropositions as $idProposition) {
                $proposition = (new PropositionRepository())->select($idProposition);
                if ($proposition->isVisible()) return $idProposition;
            }
        }
        return null;
    }

    /** Ajoute un 1 au score de fusion (utile dans les cas d'erreur) */
    public static function ajouterScoreQuestion():void {
        if (self::estConnecte()) {
            $utilisateur = self::getUtilisateurConnecte();
            (new UtilisateurRepository())->ajouterScoreQuestion($utilisateur->getLogin());
        }
    }
}