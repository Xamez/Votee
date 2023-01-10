<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\MotDePasse;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Groupe;
use App\Votee\Model\Repository\GroupeRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerGroupe extends AbstractController {

    public static function readAllGroupe():void {
        self::redirectAdmin("?controller=question&action=all");
        $groupes = (new GroupeRepository())->selectAll();
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des groupes",
                "cheminVueBody" => "groupe/listGroupe.php",
                "title" => "Liste des groupes",
                "groupes" => $groupes
            ]);
    }

    public static function readGroupe():void {
        self::redirectConnexion("?controller=utilisateur&action=connexion");
        $groupe = (new GroupeRepository())->select($_GET['idGroupe']);
        $membres =  (new GroupeRepository())->selectMembres($_GET['idGroupe']);
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des groupes",
                "cheminVueBody" => "groupe/readGroupe.php",
                "title" => $groupe->getNomGroupe(),
                "groupe" => $groupe,
                "membres" => $membres
            ]);
    }

    public static function createGroupe():void {
        self::redirectAdmin("?controller=question&action=all");
        self::afficheVue('view.php',
            [
                "pagetitle" => "Création d'un groupe",
                "cheminVueBody" => "groupe/createGroupe.php",
                "title" => "Création d'un groupe",
            ]);
    }

    public static function createdGroupe():void {
        self::redirectAdmin("?controller=question&action=all");
        $groupe = new Groupe(null, $_POST['nomGroupe']);
        $idGroupe = (new GroupeRepository())->sauvegarderSequence($groupe);
        if ($idGroupe) {
            (new Notification())->ajouter("success", "Le groupe a bien été créé.");
            self::redirection("?controller=groupe&action=addMembre&idGroupe=$idGroupe");
        } else {
            (new Notification())->ajouter("danger", "La création du groupe à échoué.");
            self::redirection("?controller=groupe&action=readAllGroupe");
        }
    }

    public static function addMembre():void {
        self::redirectAdmin("?controller=question&action=all");
        $exception = (new UtilisateurRepository())->selectAllAdmins();
        $utilisateurs = (new UtilisateurRepository())->selectAll();
        $membres = (new GroupeRepository())->selectMembres($_GET['idGroupe']);
        if ($membres) $exception = array_merge($exception, $membres);
        $utilisateur = array_udiff($utilisateurs, $exception, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });
        self::afficheVue('view.php',
            [
                "pagetitle" => "Ajouter des membres",
                "cheminVueBody" => "groupe/addMembre.php",
                "title" => "Ajouter des membres",
                "utilisateurs" => $utilisateur,
                "membres" => $membres,
                "idGroupe" => $_GET['idGroupe']
            ]);
    }

    public static function addedMembre():void {
        self::redirectAdmin("?controller=question&action=all");
        $idGroupe = $_POST['idGroupe'];
        $isOk = true;
        $oldMembres = (new GroupeRepository())->selectMembres($idGroupe);
        $membres = [];
        foreach ($oldMembres as $membre) $membres[] = $membre->getLogin();
        if (array_key_exists('membres', $_POST)) $membres = array_diff($membres, $_POST['membres']);
        if (isset($_POST['utilisateurs'])) {
            foreach ($_POST['utilisateurs'] as $login) {
                $isOk = (new GroupeRepository())->ajouterAGroupe($idGroupe, $login);
            }
        }
        foreach ($membres as $login) {
            $isOk = (new GroupeRepository())->supprimerDeGroupe($idGroupe, $login);
        }
        if ($isOk) (new Notification())->ajouter("success", "Les membres ont bien été mis a jour.");
        else (new Notification())->ajouter("warning", "Certains membres n'ont pas été mis à jour.");
        self::redirection("?controller=groupe&action=readGroupe&idGroupe=$idGroupe");
    }

    public static function deleteGroupe(): void {
        self::redirectAdmin("?controller=question&action=all");
        $idGroupe = $_GET['idGroupe'];
        self::afficheVue('view.php',
            [
                "pagetitle" => "Suppression",
                "cheminVueBody" => "groupe/deleteGroupe.php",
                "title" => "Suppression d'un groupe",
                "idGroupe" => $idGroupe
            ]);
    }

    public static function deletedGroupe(): void {
        self::redirectAdmin("?controller=question&action=all");
        $idGroupe = $_POST['idGroupe'];
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();

        if (!MotDePasse::verifier($_POST['motDePasse'], $utilisateur->getMotDePasse())) {
            (new Notification())->ajouter("warning", "Mot de passe incorrect !");
            self::redirection("?controller=groupe&action=deleteGroupe&idGroupe=$idGroupe");
        }

        $isOk = (new GroupeRepository())->supprimer($idGroupe);
        if ($isOk) (new Notification())->ajouter("success", "Le groupe a bien été supprimé.");
        else (new Notification())->ajouter("danger", "La suppression à échoué.");
        self::redirection("?controller=groupe&action=readAllGroupe");
    }

    public static function updateGroupe(): void {
        self::redirectAdmin("?controller=question&action=all");
        $idGroupe = $_GET['idGroupe'];
        $groupe = (new GroupeRepository())->select($idGroupe);
        self::afficheVue('view.php',
            [
                "pagetitle" => "Modification d'un groupe",
                "cheminVueBody" => "groupe/updateGroupe.php",
                "title" => "Modification d'un groupe",
                "subtitle" => $groupe->getNomGroupe(),
                "idGroupe" => $idGroupe,
                "groupe" => $groupe
            ]);
    }

    public static function updatedGroupe(): void {
        self::redirectAdmin("?controller=question&action=all");
        $idGroupe = $_POST['idGroupe'];
        $groupe = (new GroupeRepository())->select($idGroupe);
        $groupe->setNomGroupe($_POST['nomGroupe']);
        $isOk = (new GroupeRepository())->modifier($groupe);
        if ($isOk) {
            (new Notification())->ajouter("success", "Le groupe a bien été modifié.");
            self::redirection("?controller=groupe&action=readGroupe&idGroupe=$idGroupe");
        } else {
            (new Notification())->ajouter("danger", "La modification à échoué");
            self::redirection("?controller=groupe&action=updateGroupe&idGroupe=$idGroupe");
        }
    }

}