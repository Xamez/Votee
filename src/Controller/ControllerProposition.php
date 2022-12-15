<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Texte;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\TexteRepository;
use App\Votee\Model\Repository\UtilisateurRepository;
use App\Votee\parsedown\Parsedown;

class ControllerProposition extends AbstractController{

    public static function createProposition(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if (!ConnexionUtilisateur::estConnecte()
            || !ConnexionUtilisateur::creerProposition($question->getIdQuestion())
            || ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'representant') {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une proposition !");
            self::redirection("?controller=question&all");
        }
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        if ($question) {
            self::afficheVue('view.php',
                [
                    "pagetitle" => "Creation",
                    "sections" => $sections,
                    "representant" => ConnexionUtilisateur::getUtilisateurConnecte(),
                    "idQuestion" => $_GET['idQuestion'],
                    "cheminVueBody" => "proposition/createProposition.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()
                ]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdProposition(): void {
        $question = (new QuestionRepository())->select($_POST['idQuestion']);
        if (!ConnexionUtilisateur::estConnecte()
            || !ConnexionUtilisateur::creerProposition($question->getIdQuestion())
            || ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'representant') {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une proposition !");
            self::redirection("?controller=question&all");
        }
        $idProposition = (new PropositionRepository())->ajouterProposition('visible');
        $isOk = true;
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $textsection = nl2br(htmlspecialchars($_POST['section' . $i]));
            $texte = new Texte(
                $_POST['idQuestion'],
                $_POST['idSection' . $i],
                $idProposition,
                $textsection,
                NULL
            );
            $isOk = (new TexteRepository())->sauvegarder($texte);
        }
        $isOk &= (new PropositionRepository())->ajouterRepresentant($_POST['organisateur'], $idProposition, $_POST['idQuestion'], 0);
        if ($isOk) (new Notification())->ajouter("success", "La proposition a été créée.");
        else {
            (new PropositionRepository())->supprimer($idProposition);
            (new Notification())->ajouter("warning", "L'ajout de la proposition a échoué.");
        }
        self::redirection("?controller=question&action=readQuestion&idQuestion=" . $_POST['idQuestion']);
    }

    public static function propositionHeader(): void {
        $responsable = (new UtilisateurRepository())->selectResp($_GET['idProposition']);
        $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']);
        self::afficheVue('view.php',
            [
                "responsable" => $responsable,
                "coAuteurs" => $coAuteurs,
                "pagetitle" => "Suppression",
                "cheminVueBody" => "proposition/deleteProposition.php",
                "title" => "Supression d'un vote",
            ]);
    }

    public static function updateProposition(): void {
        $idProposition = $_GET['idProposition'];
        $proposition = (new PropositionRepository())->select($idProposition);
        if ($proposition->getVisibilite == 'invisible' || !ConnexionUtilisateur::getRoleProposition($idProposition) == 'representant'
            || !ConnexionUtilisateur::getRoleProposition($idProposition) == 'coauteur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&all");
        }
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $textes = (new TexteRepository())->selectAllByKey($idProposition);
        if ($question && $textes) {
            $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
            $responsable = (new UtilisateurRepository())->selectResp($idProposition);
            $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($idProposition);
            self::afficheVue('view.php',
                [
                    "question" => $question,
                    "idProposition" => $_GET['idProposition'],
                    "sections" => $sections,
                    "coAuteurs" => $coAuteurs,
                    "textes" => $textes,
                    "responsable" => $responsable,
                    "pagetitle" => "Edition de proposition",
                    "cheminVueBody" => "proposition/updateProposition.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()
                ]);
        } else {
            self::error("La proposition ou la question n'existe pas.");
        }
    }

    public static function updatedProposition(): void {
        $idQuestion = $_POST['idQuestion'];
        $idProposition = $_POST['idProposition'];
        $proposition = (new PropositionRepository())->select($idProposition);
        if ($proposition->getVisibilite == 'invisible' || !ConnexionUtilisateur::getRoleProposition($idProposition) == 'representant'
            || !ConnexionUtilisateur::getRoleProposition($idProposition) == 'coauteur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
        $isOk = true;
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $textsection = nl2br(htmlspecialchars($_POST['section' . $i]));
            $texte = new Texte($_POST['idQuestion'], $_POST['idSection' . $i], $idProposition, $textsection, NULL);
            $isOk = (new TexteRepository())->modifier($texte);
        }
        if ($_POST['coAuteur'] != "") {
            $isOk &= (new PropositionRepository())->ajouterCoauteur($_POST['coAuteur'], $idProposition);
        }

        if ($isOk) (new Notification())->ajouter("success", "La proposition a été modifiée.");
        else (new Notification())->ajouter("danger", "La proposition n'a pas pu être modifiée.");
        self::redirection("?controller=proposition&action=readProposition&idQuestion=" . $_POST['idQuestion'] . "&idProposition=" . $_POST['idProposition']);
    }

    public static function readProposition(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $textes = (new TexteRepository())->selectAllByKey($_GET['idProposition']);
        foreach ($textes as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }
        if ($question && $textes) {
            $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
            $responsable = (new UtilisateurRepository())->selectResp($_GET['idProposition']);
            $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']);
            self::afficheVue('view.php',
                [
                    "visibilite" => $proposition->getVisibilite(),
                    "question" => $question,
                    "idProposition" => $_GET['idProposition'],
                    "sections" => $sections,
                    "coAuteurs" => $coAuteurs,
                    "textes" => $textes,
                    "responsable" => $responsable,
                    "pagetitle" => "Question",
                    "cheminVueBody" => "proposition/readProposition.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()
                ]);
        } else {
            self::error("La proposition ou la question n'existe pas");
        }
    }

    public static function deleteProposition(): void {
        $idProposition = $_GET['idProposition'];
        $proposition = (new PropositionRepository())->select($idProposition);
        if ($proposition->getVisibilite == 'invisible' || !ConnexionUtilisateur::getRoleProposition($idProposition) == 'representant'
            || ConnexionUtilisateur::getRoleProposition($idProposition) == 'coauteur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        $idQuestion = $_GET['idQuestion'];
        self::afficheVue('view.php',
            [
                "idQuestion" => $idQuestion,
                "idProposition" => $idProposition,
                "pagetitle" => "Confirmation",
                "cheminVueBody" => "proposition/deleteProposition.php",
                "title" => "Confirmation de suppression",
            ]);
    }

    public static function deletedProposition(): void {
        $idProposition = $_GET['idProposition'];
        $proposition = (new PropositionRepository())->select($idProposition);
        if ($proposition->getVisibilite == 'invisible' ||!ConnexionUtilisateur::getRoleProposition($_GET['idProposition']) == 'representant'
            || ConnexionUtilisateur::getRoleProposition($_GET['idProposition']) == 'coauteur') {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        if ((new PropositionRepository())->supprimer($idProposition)) {
            (new Notification())->ajouter("success", "La proposition a été supprimée.");
        } else (new Notification())->ajouter("warning", "La proposition n'a pas pu être supprimée.");
        self::redirection("?controller=question&action=readQuestion&idQuestion=" . $_GET['idQuestion']);
    }


    public static function createdCoAuteur():void {
        $idProposition = $_POST['idProposition'];
        $login = $_POST['login'];
        if ((new PropositionRepository())->ajouterCoAuteur( $login, $idProposition)) {
            (new Notification())->ajouter("success", "Le co-auteur a été ajouté.");
        } else (new Notification())->ajouter("warning", "Le co-auteur n'a pas pu être ajouté.");
        self::redirection("?controller=proposition&action=updateProposition&idQuestion=" . $_POST['idQuestion'] . "&idProposition=" . $_POST['idProposition']);
    }

    public static function deletedCoAuteur(): void {
        $idProposition = $_GET['idProposition'];
        $login = $_GET['login'];
        if ((new PropositionRepository())->supprimerCoAuteur( $login, $idProposition)) {
            (new Notification())->ajouter("success", "Le co-auteur a été supprimé.");
        } else (new Notification())->ajouter("warning", "Le co-auteur n'a pas pu être supprimé.");
        self::redirection("?action=updateProposition&idQuestion=" . $_GET['idQuestion'] . "&idProposition=" . $_GET['idProposition']);
    }


//    public static function selectFusion(): void {
//
//
//        $question = (new QuestionRepository())->select($_GET['idQuestion']);
//        self::afficheVue('view.php',
//            [
//                "pagetitle" => "Créer la fusion",
//                "cheminVueBody" => "proposition/selectFusion.php",
//                "title" => $question->getTitre(),
//                "subtitle" => $question->getDescription()
//            ]);
//    }

    public static function createFusion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);

        $idPropAMerge = ConnexionUtilisateur::getPropByLogin($_GET['idQuestion']);

        $textesCourant = (new TexteRepository())->selectAllByKey($_GET['idProposition']);
        foreach ($textesCourant as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }

        $texteAMerge = (new TexteRepository())->selectAllByKey($idPropAMerge);
        foreach ($texteAMerge as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }

        $respCourant = (new UtilisateurRepository())->selectResp($_GET['idProposition']);
        $respAMerge = (new UtilisateurRepository())->selectResp($idPropAMerge);

        $coAuteursCourant = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']);
        $coAuteursAMerge = (new UtilisateurRepository())->selectCoAuteur($idPropAMerge);


        $coAuteurs[] = $respCourant;
        //$coAuteurs[] = array_unique(array_merge($coAuteursCourant, $coAuteursAMerge), SORT_REGULAR);
        //if (in_array($respCourant, $coAuteurs)) unset($coAuteurs[array_search($respCourant, $coAuteurs)]);
        // TODO gerer les coaAuteur
        self::afficheVue('view.php',
            [
                "pagetitle" => "Lire la fusion",
                "idPropositions" => array($_GET['idProposition'], $idPropAMerge),
                "question" => $question,
                "sections" => $sections,
                "coAuteurs" => $coAuteurs,
                "textes" => array($textesCourant, $texteAMerge),
                "responsable" => $respAMerge, // Proposition header
                "responsables" => array($respCourant, $respAMerge),
                "cheminVueBody" => "proposition/createFusion.php",
                "title" => $question->getTitre(),
                "subtitle" => $question->getDescription()
            ]);
    }

    public static function createdFusion(): void {
        (new PropositionRepository())->modifierProposition($_POST['idPropCourant'], 'invisible', null);
        (new PropositionRepository())->modifierProposition($_POST['idPropAMerge'], 'invisible', null);
        $idNewProp = (new PropositionRepository())->ajouterProposition('visible');
        (new PropositionRepository())->modifierProposition($_POST['idPropCourant'], 'invisible', $idNewProp);
        (new PropositionRepository())->modifierProposition($_POST['idPropAMerge'], 'invisible', $idNewProp);
        $idOldProp = $_POST['idPropCourant'];
        $isOk = true;
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $texte = new Texte($_POST['idQuestion'], $_POST['idSection' . $i], $idNewProp, $_POST['section' . $i], null);
            $isOk = (new TexteRepository())->sauvegarder($texte);
        }
        foreach ($_POST['coAuteurs'] as $coAuteur) {
            $isOk &= (new PropositionRepository())->ajouterCoauteur($coAuteur, $idNewProp);
        }
        $isOk &= (new PropositionRepository())->ajouterRepresentant($_POST['newResp'], $idNewProp, $idOldProp, $_POST['idQuestion'], 1);

        if ($isOk) (new Notification())->ajouter("success", "La fusion a été réalisée avec succès.");
        else {
            (new PropositionRepository())->supprimer($idNewProp);
            (new PropositionRepository())->modifierProposition($_POST['idPropCourant'], 'visible', null);
            (new PropositionRepository())->modifierProposition($_POST['idPropAMerge'], 'visible', null);
            (new Notification())->ajouter("danger", "La fusion n'a pas pu être réalisée.");
        }
        self::redirection("?controller=question&action=readQuestion&idQuestion=" . $_POST['idQuestion']);
    }

}