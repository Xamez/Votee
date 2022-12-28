<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Demande;
use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerDemande extends AbstractController {

    public static function readAllDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=all");
        }
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $demandes = (new DemandeRepository())->getDemandeByDest($utilisateur->getLogin());
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
                "pagetitle" => "Liste des demandes",
                "cheminVueBody" => "demande/listDemande.php",
                "title" => "Liste des demandes",
            ]);
    }

    public static function readDemande(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=all");
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
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($demande->getIdQuestion());
        $rolesProp = ConnexionUtilisateur::getRolesProposition($demande->getIdProposition());
        if ($demande->getEtatDemande() != 'attente') {
            (new Notification())->ajouter("danger","La demande a déjà été traitée !");
            self::redirection("?controller=demande&action=readAllDemande");
        }
        if (!(($demande->getTitreDemande() == 'fusion' && in_array('Responsable',$rolesProp))
            || ($demande->getTitreDemande() == 'question' && ConnexionUtilisateur::estAdministrateur())
            || ($demande->getTitreDemande() == 'proposition' && in_array('Organisateur',$rolesQuest)))) {
            (new Notification())->ajouter("danger","Vous n'avez pas les permissions !");
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
            self::redirection("?controller=question&action=all");
        }
        $titreDemande = $_GET['titreDemande'];
        if (!in_array($titreDemande, ['question','fusion', 'proposition'], true )) {
            (new Notification())->ajouter("danger","Erreur lors du chargement de la page !");
            self::redirection("?controller=question&action=all");
        }
        $idQuestion = array_key_exists('idQuestion', $_GET) ? $_GET['idQuestion'] : null;
        $idProposition = array_key_exists('idProposition', $_GET) ? $_GET['idProposition'] : null;
        // TODO verifier que idQUestion ne change pas et jsp comment faire
        self::afficheVue('view.php',
            [
                "idQuestion" => $idQuestion,
                "idProposition" => $idProposition,
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
            self::redirection("?controller=question&action=all");
        }
        $idProposition = array_key_exists('idProposition', $_POST) ? $_POST['idProposition'] : null;
        $idQuestion = array_key_exists('idQuestion', $_POST) ? $_POST['idQuestion'] : null;
        $destinataire = '';
        if ($_POST['titreDemande'] == 'question') $destinataire = 'admin';
        else if ($_POST['titreDemande'] == 'proposition') $destinataire = (new QuestionRepository())->select($idQuestion)->getLogin();
        else if ($_POST['titreDemande'] == 'fusion') $destinataire = (new UtilisateurRepository())->selectResp($idProposition)->getLogin();
        $demande = new Demande(
            $destinataire,
            ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(),
            null,
            'attente',
            $_POST['titreDemande'],
            $_POST['motif'],
            $idProposition,
            $idQuestion
        );
        $isOk = (new DemandeRepository())->ajouterDemande($demande);
        if ($isOk) (new Notification())->ajouter("success", "La demande a été envoyée !");
        else (new Notification())->ajouter("warning", "La demande n'a pas été envoyée !");
        self::redirection("?controller=utilisateur&action=historiqueDemande");
    }
}