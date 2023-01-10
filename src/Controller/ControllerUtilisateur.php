<?php

namespace App\Votee\Controller;

use App\Votee\Lib\MotDePasse;
use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Utilisateur;
use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Model\Repository\GroupeRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur extends AbstractController {


    public static function authentification(): void {
        $utilisateur = (new UtilisateurRepository())->select($_POST['login']);
        if ($utilisateur) {
            if (MotDePasse::verifier($_POST['password'], $utilisateur->getMotDePasse())) {
                (new ConnexionUtilisateur())->connecter($utilisateur->getLogin());
                (new Notification())->ajouter("success","Connection réussie.");
                self::redirection("?controller=question&action=all");
            } else {
                (new Notification())->ajouter("warning","Mot de passe incorrect.");
                self::redirection("?controller=utilisateur&action=connexion");
            }
        } else {
            (new Notification())->ajouter("warning","Utilisateur inconnu.");
            self::redirection("?controller=utilisateur&action=connexion");
        }
    }

    public static function inscrit(): void {
        if ($_POST['password'] != $_POST['passwordVerif']) {
            (new Notification())->ajouter("warning", "Mots de passe distincts.");
            self::redirection("?controller=utilisateur&action=inscription");
        }
        $utilisateur = Utilisateur::construireDepuisFormulaire($_POST);
        $isOk = (new UtilisateurRepository())->sauvegarder($utilisateur);
        if ($isOk) {
            (new Notification())->ajouter("success","Votre compte a été créé.");
            if (MotDePasse::verifier($_POST['password'], $utilisateur->getMotDePasse())) {
                (new ConnexionUtilisateur())->connecter($utilisateur->getLogin());
                self::redirection("?controller=question&action=all");
            } else {
                (new Notification())->ajouter("warning","La connexion a échoué.");
                self::redirection("?controller=utilisateur&action=connexion");
            }
        } else {
            (new Notification())->ajouter("warning","L'inscription a échoué.");
            self::redirection("?controller=utilisateur&action=inscription");
        }
    }

    public static function deconnecter(): void {
        (new ConnexionUtilisateur())->deconnecter();
        (new Notification())->ajouter("success","Déconnexion réussie.");
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
            $questionsSpecia = (new QuestionRepository())->selectQuestionSpecia($login);
        }
        self::afficheVue('view.php',
            [
                "questionsOrga" => $questionsOrga,
                "questionsRepre" => $questionsRepre,
                "questionsCoau" => $questionsCoau,
                "questionsVota" => $questionsVota,
                "questionsSpecia" => $questionsSpecia,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "question/listQuestion.php",
                "title" => "Liste des questions",
            ]);
    }

    public static function historiqueDemande(): void {
        self::redirectConnexion("?controller=utilisateur&action=connexion");
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

    public static function readUtilisateur(): void {
        $login = $_GET['login'] ?? ConnexionUtilisateur::getUtilisateurConnecte()->getLogin();
        $utilisateur = (new UtilisateurRepository())->select($login);
        $groupes = (new GroupeRepository())->selectGroupeByLogin($login);
        self::afficheVue('view.php',
            [
                "utilisateur" => $utilisateur,
                "groupes" => $groupes,
                "pagetitle" => "Profil utilisateur",
                "cheminVueBody" => "utilisateur/readUtilisateur.php",
                "title" => "Profil utilisateur",
            ]);
    }

    public static function updateUtilisateur(): void {
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $groupes = (new GroupeRepository())->selectGroupeByLogin($utilisateur->getLogin());
        self::afficheVue('view.php',
            [
                "utilisateur" => $utilisateur,
                "groupes" => $groupes,
                "pagetitle" => "Utilisateur",
                "cheminVueBody" => "utilisateur/updateUtilisateur.php",
                "title" => "Utilisateur",
            ]);
    }

    public static function updatedUtilisateur(): void {
        $motDePasse = $_POST['motDePasse'];
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        if (!MotDePasse::verifier($motDePasse, $utilisateur->getMotDePasse())) {
            (new Notification())->ajouter("warning", "Mot de passe incorrect.");
            self::redirection("?controller=utilisateur&action=updateUtilisateur");
        } else {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $description = $_POST['description'];
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setDescription($description);
            if ((new UtilisateurRepository())->modifier($utilisateur)) {
                (new Notification())->ajouter("success", "Votre compte a été modifié.");
                self::redirection("?controller=utilisateur&action=updateUtilisateur");
            } else {
                (new Notification())->ajouter("warning", "La modification à échoué.");
                self::redirection("?controller=utilisateur&action=updateUtilisateur");
            }
        }
    }

    public static function confirmUpdateUtilisateur(): void {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $description = $_POST['description'];
        self::afficheVue('view.php',
            [
                "nom" => $nom,
                "prenom" => $prenom,
                "description" => $description,
                "pagetitle" => "Confirmer les modifications",
                "cheminVueBody" => "utilisateur/confirmUpdateUtilisateur.php",
                "title" => "Utilisateur",
            ]);
    }

    public static function updateMdpUtilisateur(): void {
        self::afficheVue('view.php',
            [
                "pagetitle" => "Changement mot de passe",
                "cheminVueBody" => "utilisateur/updateMdpUtilisateur.php",
                "title" => "Utilisateur",
            ]);
    }

    public static function updatedMdpUtilisateur(): void {
        $ancienMotDePasse = $_POST['ancienMotDePasse'];
        $nouveauMotDePasse = $_POST['nouveauMotDePasse'];
        $nouveauMotDePasseConfirm = $_POST['nouveauMotDePasseConfirm'];
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        if (!MotDePasse::verifier($ancienMotDePasse, $utilisateur->getMotDePasse())) {
            (new Notification())->ajouter("warning", "Mot de passe incorrect.");
            self::redirection("?controller=utilisateur&action=updateUtilisateur");
        } else {
            if ($nouveauMotDePasse != $nouveauMotDePasseConfirm) {
                (new Notification())->ajouter("warning", "Mots de passe distincts.");
                self::redirection("?controller=utilisateur&action=updateUtilisateur");
            }
            if ($nouveauMotDePasse == $ancienMotDePasse) {
                (new Notification())->ajouter("warning", "Le nouveau mot de passe est identique à l'ancien.");
                self::redirection("?controller=utilisateur&action=updateUtilisateur");
            } else {
                $utilisateur->setMotDePasse($nouveauMotDePasse);
                if ((new UtilisateurRepository())->modifier($utilisateur)) {
                    (new Notification())->ajouter("success", "Le mot de passe a été modifié.");
                    self::redirection("?controller=utilisateur&action=updateUtilisateur");
                } else {
                    (new Notification())->ajouter("warning", "La modification à échoué.");
                    self::redirection("?controller=utilisateur&action=updateUtilisateur");
                }
            }
        }
    }

}