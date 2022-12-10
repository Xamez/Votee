<?php

namespace App\Votee\Controller;

use App\Votee\Lib\MotDePasse;
use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur extends AbstractController {


    public static function authentification(): void {
        $utilisateur = (new UtilisateurRepository())->select($_POST['login']);
        if ($utilisateur) {
            if (MotDePasse::verifier($_POST['password'], $utilisateur->getMotDePasse())) {
                var_dump($utilisateur);
                (new ConnexionUtilisateur())->connecter($utilisateur->getLogin());
                (new Notification())->ajouter("success","L'utilisateur est connecté");
                self::redirection("?controller=question&action=readAllQuestion");
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
        self::redirection("?controller=question&action=readAllQuestion");
    }

    public static function deconnecter(): void {
        (new ConnexionUtilisateur())->deconnecter();
        (new Notification())->ajouter("success","L'utilisateur est déconnecté");
        self::redirection("?controller=question&action=readAllQuestion");
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

}