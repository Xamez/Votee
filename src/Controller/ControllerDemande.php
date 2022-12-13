<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Demande;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerDemande extends AbstractController {

    public static function readAllDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=readAllQuestion");
        }
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $demandes = (new DemandeRepository())->getDemandeByDest($utilisateur->getLogin());
        $demandesAccepte = [];
        $demandesRefuse = [];
        $demandesAttente = [];
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
                "pagetitle" => "Liste des demandes",
                "cheminVueBody" => "demande/listDemande.php",
                "title" => "Liste des demandes",
            ]);
    }

    public static function historiqueDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=readAllQuestion");
        }
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $demandes = (new DemandeRepository())->getDemandeByUtil($utilisateur->getLogin());
        $demandesAccepte = [];
        $demandesRefuse = [];
        $demandesAttente = [];
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

    public static function readDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=readAllQuestion");
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
        $demande = (new DemandeRepository())->select($_GET['idDemande']);
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estOrganisateur($demande->getIdQuestion())) {
            (new Notification())->ajouter("danger","Vous devez être administrateur ou organisateur de la question !");
            self::redirection("?controller=demande&action=readAllDemande");
        }
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

    public static function createDemande(): void {;
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=readAllQuestion");
        }
        $titreDemande = $_GET['titreDemande'];
        if (!in_array($titreDemande, ['question','fusion', 'proposition'], true )) {
            (new Notification())->ajouter("danger","Erreur lors du chargement de la page !");
            self::redirection("?controller=question&action=readAllQuestion");
        }
        // TODO verifier que idQUestion ne change pas et jsp comment faire
        self::afficheVue('view.php',
            [
                "idQuestion" => $_GET['idQuestion'],
                "titreDemande" => $titreDemande,
                "pagetitle" => "Demande",
                "cheminVueBody" => "demande/createDemande.php",
                "title" => "Demande",
                "subtitle" => "Définissez un motif de demande."
            ]);
    }

    public static function createdDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger", "Vous devez vous connecter !");
            self::redirection("?controller=question&action=readAllQuestion");
        }
        $idProposition = array_key_exists('idProposition', $_POST) ? $_POST['idProposition'] : null;
        $idQuestion = array_key_exists('idQuestion', $_POST) ? $_POST['idQuestion'] : null;
        var_dump($idQuestion);
        $destinataire = '';
        if ($_POST['titreDemande'] == 'question') $destinataire = 'admin';
        else if ($_POST['titreDemande'] == 'proposition') $destinataire = (new QuestionRepository())->select($idQuestion)->getLogin();

        // else if ($_POST['titreDemande'] == 'fusion') $destinataire = 'admin';
        $demande = new Demande(
            $destinataire,
            ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(),
            null,
            $_POST['motif'],
            $_POST['titreDemande'],
            'attente',
            $idProposition,
            $idQuestion
        );
        $isOk = (new DemandeRepository())->ajouterDemande($demande);
        if ($isOk) (new Notification())->ajouter("success", "La demande a été envoyée !");
        else (new Notification())->ajouter("warning", "La demande n'a pas été envoyée !");
        self::redirection("?controller=demande&action=readAllDemande");
    }
}