<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
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
        $utilisateurs = [];
        $demandesAccepte = [];
        $demandesRefuse = [];
        $demandesAttente = [];
        foreach ($demandes as $demande) {
            if ($demande->getEtatDemande() == 'accepte') $demandesAccepte[] = $demande;
            if ($demande->getEtatDemande() == 'refuse') $demandesRefuse[] = $demande;
            if ($demande->getEtatDemande() == 'attente') $demandesAttente[] = $demande;
            $utilisateurs[] = (new UtilisateurRepository())->select($demande->getLogin());
        }

        self::afficheVue('view.php',
            [
                "demandesAccepte" => $demandesAccepte,
                "demandesRefuse" => $demandesRefuse,
                "demandesAttente" => $demandesAttente,
                "utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des demandes",
                "cheminVueBody" => "demande/listDemande.php",
                "title" => "Liste des demandes",
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
            self::redirection("?controller=question&readAllQuestion");
        }
        $demande = (new DemandeRepository())->select($_GET['idDemande']);
        $demande->setEtatDemande($_GET['statut']);
        (new DemandeRepository())->update($demande);
        (new Notification())->ajouter("success","La demande a été mise à jour !");
        self::redirection("?controller=demande&action=readAllDemande");
    }
}