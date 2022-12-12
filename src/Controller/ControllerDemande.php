<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Demande;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerDemande extends AbstractController {

    public static function readAllDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&readAllQuestion");
        }
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $demandes = (new DemandeRepository())->getDemandeByDest($utilisateur->getLogin());
        $demandesAccepte = [];
        $demandesRefuse = [];
        $demandesAttente = [];
        foreach ($demandes as $demande) {
            $utilisateur = (new UtilisateurRepository())->select($demande->getLogin());
            if ($demande->getEtatDemande() == 'accepte') $demandesAccepte[$demande->getIdDemande()] = $utilisateur;
            if ($demande->getEtatDemande() == 'refuse') $demandesRefuse[$demande->getIdDemande()] = $utilisateur;
            if ($demande->getEtatDemande() == 'attente') $demandesAttente[$demande->getIdDemande()] = $utilisateur;
        }

        self::afficheVue('view.php',
            [
                "demandesAccepte" => $demandesAccepte,
                "demandesRefuse" => $demandesRefuse,
                "demandesAttente" => $demandesAttente,
                "pagetitle" => "Liste des demandes",
                "cheminVueBody" => "demande/listDemande.php",
                "title" => "Liste des demandes",
            ]);
    }

    public static function historiqueDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&readAllQuestion");
        }
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $demandes = (new DemandeRepository())->getDemandeByUtil($utilisateur->getLogin());
        $demandesAccepte = [];
        $demandesRefuse = [];
        $demandesAttente = [];
        foreach ($demandes as $demande) {
            $utilisateur = (new UtilisateurRepository())->select($demande->getLogin());
            if ($demande->getEtatDemande() == 'accepte') $demandesAccepte[$demande->getIdDemande()] = $utilisateur;
            if ($demande->getEtatDemande() == 'refuse') $demandesRefuse[$demande->getIdDemande()] = $utilisateur;
            if ($demande->getEtatDemande() == 'attente') $demandesAttente[$demande->getIdDemande()] = $utilisateur;
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

    public static function readDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&readAllQuestion");
        }
        $demande = (new DemandeRepository())->select($_GET['idDemande']);
        $auteur = (new UtilisateurRepository())->select($demande->getLogin());
        self::afficheVue('view.php',
            [
                "demande" => $demande,
                "auteur" => $auteur,
                "pagetitle" => "Demande",
                "cheminVueBody" => "demande/readDemande.php",
                "title" => "Demande",
            ]);
    }

    public static function setDemande(): void {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            (new Notification())->ajouter("danger","Vous devez être administrateur !");
            self::redirection("?controller=demande&action=readAllDemande");
        }
        $demande = (new DemandeRepository())->select($_GET['idDemande']);
        if ($demande->getEtatDemande() != 'attente') {
            (new Notification())->ajouter("danger","La demande a déjà été traitée !");
            self::redirection("?controller=demande&action=readAllDemande");
        }
        $demande->setEtatDemande($_GET['statut']);
        $isOk = (new DemandeRepository())->updateDemande($demande);
        if ($isOk) (new Notification())->ajouter("success","La demande a été mise à jour !");
        else (new Notification())->ajouter("warning","La demande n'a pas été mise à jour !");
        self::redirection("?controller=demande&action=readAllDemande");
    }

    public static function createDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&readAllQuestion");
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Demande",
                "cheminVueBody" => "demande/createDemande.php",
                "title" => "Demande",
                "subtitle" => "Définissez un motif de demande."
            ]);
    }

    public static function createdDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger", "Vous devez vous connecter !");
            self::redirection("?controller=question&readAllQuestion");
        }
        $demande = new Demande(
            'admin',
            ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(),
            null,
            $_POST['motif'],
            'Organisateur',
            'attente');
        $isOk = (new DemandeRepository())->ajouterDemande($demande);
        if ($isOk) (new Notification())->ajouter("success", "La demande a été envoyée !");
        else (new Notification())->ajouter("warning", "La demande n'a pas été envoyée !");
        self::redirection("?controller=demande&action=readAllDemande");
    }
}