<?php

namespace App\Votee\Controller;

use App\Votee\Lib\MotDePasse;
use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur extends AbstractController {


    public static function authentification(): void {
        $utilisateur = (new UtilisateurRepository())->select($_POST['login']);
        if ($utilisateur) {
            if (MotDePasse::verifier($_POST['password'], $utilisateur->getMotDePasse())) {
                (new ConnexionUtilisateur())->connecter($utilisateur->getLogin());
                (new Notification())->ajouter("success","L'utilisateur est connecté");
                self::redirection("?controller=question&action=all");
            } else {
                (new Notification())->ajouter("warning","Mot de passe erroné");
                self::redirection("?controller=utilisateur&action=connexion");
            }
        } else {
            (new Notification())->ajouter("danger","Utilisateur inconnu");
            self::redirection("?controller=utilisateur&action=connexion");
        }
    }

    public static function inscrit(): void {
        if ($_POST['password'] != $_POST['passwordVerif']) {
            (new Notification())->ajouter("warning", "Mots de passe distincts");
            self::redirection("?controller=utilisateur&action=inscription");
        }
        $utilisateur = Utilisateur::construireDepuisFormulaire($_POST);
        (new UtilisateurRepository())->sauvegarder($utilisateur);
        (new Notification())->ajouter("success","L'utilisateur a été créé");
        (new ConnexionUtilisateur())->connecter($utilisateur->getLogin());
        self::redirection("?action=home");
    }

    public static function deconnecter(): void {
        (new ConnexionUtilisateur())->deconnecter();
        (new Notification())->ajouter("success","L'utilisateur est déconnecté");
        self::redirection("?action=home");
    }

    public static function connexion(): void {
        self::afficheVue("view.php",
            [
                "pagetitle" => "Connexion",
                "cheminVueBody" => "utilisateur/connexion.php",
                "title" => "Se connecter",
            ]);
    }

    public static function inscription(): void {
        self::afficheVue("view.php",
            [
                "pagetitle" => "Inscription",
                "cheminVueBody" => "utilisateur/inscription.php",
                "title" => "S'inscrire",
            ]);
    }

    public static function compte(): void {
        self::afficheVue("view.php",
            [
                "pagetitle" => "Compte",
                "cheminVueBody" => "utilisateur/compte.php",
                "title" => "Mon compte",
            ]);
    }

    public static function information(): void {
        self::afficheVue("view.php",
            [
                "pagetitle" => "Compte",
                "cheminVueBody" => "utilisateur/information.php",
                "title" => "Mon compte",
            ]);
    }

    public static function readAllQuestion(): void {
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        if ($utilisateur == null) {
            $questionsOrga = $questionsRepre = $questionsCoau = $questionsVota = [];
        } else {
            $login = $utilisateur->getLogin();
            $questionsOrga = (new QuestionRepository())->selectQuestionOrga($login);
            $questionsRepre = (new QuestionRepository())->selectQuestionResp($login);
            $questionsCoau = (new QuestionRepository())->selectQuestionCoau($login);
            $questionsVota = (new QuestionRepository())->selectQuestionVota($login);
        }
        self::afficheVue('view.php',
            [
                "questionsOrga" => $questionsOrga,
                "questionsRepre" => $questionsRepre,
                "questionsCoau" => $questionsCoau,
                "questionsVota" => $questionsVota,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "question/listQuestion.php",
                "title" => "Liste des votes",
            ]);
    }

    public static function historiqueDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=readAllQuestion");
        }
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $demandes = (new DemandeRepository())->getDemandeByUtil($utilisateur->getLogin());
        $demandesAccepte = $demandesRefuse = $demandesAttente = [];
        foreach ($demandes as $demande) {
            $utilisateur = (new UtilisateurRepository())->select($demande->getLogin());
            if ($demande->getEtatDemande() == 'accepte') $demandesAccepte[] = [$utilisateur, $demande];
            if ($demande->getEtatDemande() == 'refuse') $demandesRefuse[] = [$utilisateur, $demande];
            if ($demande->getEtatDemande() == 'attente') $demandesAttente[] = [$utilisateur, $demande];
        }
        self::afficheVue('view.php',
            [
                "demandesAccepte" => $demandesAccepte,
                "demandesRefuse" => $demandesRefuse,
                "demandesAttente" => $demandesAttente,
                "pagetitle" => "Historique de mes demandes",
                "cheminVueBody" => "demande/listDemande.php",
                "title" => "Historique de mes demandes",
            ]);
    }

}