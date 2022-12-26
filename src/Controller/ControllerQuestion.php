<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Question;
use App\Votee\Model\DataObject\Section;
use App\Votee\Model\DataObject\VoteTypes;
use App\Votee\Model\Repository\GroupeRepository;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerQuestion extends AbstractController {

    public static function home() : void {
        self::afficheVue('view.php',
            [
                "pagetitle" => "Page d'accueil",
                "mainType" => 1,
                "footerType" => 1,
                "cheminVueBody" => "home.php"
            ]);
    }

    public static function section() : void {
        if (!ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer un vote !");
            self::redirection("?controller=question&all");
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Nombre de sections",
                "cheminVueBody" => "question/section.php",
                "title" => "Créer une question",
                "subtitle" => "Définissez un nombre de section pour votre question."
            ]);
    }

    public static function createQuestion(): void {
        if (!ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une question !");
            self::redirection("?controller=question&all");
        }
        $nbSections = $_POST['nbSections'];
        $voteTypes = VoteTypes::toArray();
        self::afficheVue('view.php',
            [
                "nbSections" => $nbSections,
                "voteTypes" => $voteTypes,
                "pagetitle" => "Creation",
                "cheminVueBody" => "question/createQuestion.php",
                "title" => "Créer une question",
            ]);
    }

    // Permet de voir toutes les questions du site
    public static function all() : void {
        $search = $_GET['search'] ?? null;
        if (!$search) $questions = (new QuestionRepository())->selectBySearch($search);
        else $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "question/all.php",
                "title" => "Liste des questions",
                "questions" => $questions
            ]);
    }

    public static function readQuestion() : void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if ($question) {
            $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
            $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
            $responsables = array();
            foreach ($propositions as $proposition) {
                $idProposition = $proposition->getIdProposition();
                $responsables[$idProposition] = (new UtilisateurRepository())->selectResp($idProposition);
            }
            $organisateur = (new UtilisateurRepository())->select($question->getLogin());
            self::afficheVue('view.php',
                [
                    "question" => $question,
                    "propositions" => $propositions,
                    "sections" => $sections,
                    "organisateur" => $organisateur,
                    "responsables" => $responsables,
                    "pagetitle" => "Question",
                    "cheminVueBody" => "question/readQuestion.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()
                ]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdQuestion() : void {
        if (!ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une question !");
            self::redirection("?controller=question&action=all");
        }
        $question = new Question(NULL,
            $_POST['visibilite'],
            $_POST['titreQuestion'],
            $_POST['descriptionQuestion'],
            date_format(date_create($_POST['dateDebutQuestion']), 'd/m/Y'),
            date_format(date_create($_POST['dateFinQuestion']), 'd/m/Y'),
            date_format(date_create($_POST['dateDebutVote']), 'd/m/Y'),
            date_format(date_create($_POST['dateFinVote']), 'd/m/Y'),
            $_POST['organisateur'],
            $_POST['voteType']
        );
        $idQuestion = (new QuestionRepository())->ajouterQuestion($question);
        $isOk = true;
        for ($i = 1; $i <= $_POST['nbSections'] && $isOk; $i++) {
            $section = new Section(NULL, $_POST['section' . $i], $idQuestion);
            $isOk = (new SectionRepository())->sauvegarder($section);
        }
//        foreach ($_POST['votant'] as $votant)
//            $isOk = (new QuestionRepository())->ajouterVotant($idQuestion, $votant);
//        $idPersonne = ConnexionUtilisateur::getUtilisateurConnecte()->getLogin();
//        (new QuestionRepository())->ajouterVotant($idQuestion, $idPersonne);
        if ($isOk) {
            (new Notification())->ajouter("success", "La question a été créée.");
            self::redirection("?controller=question&action=addVotant&idQuestion=" . $idQuestion);
        } else {
            (new QuestionRepository())->supprimer($idQuestion);
            (new Notification())->ajouter("warning", "L'ajout de la question a échoué.");
            self::redirection("?action=controller=question&action=createQuestion");
        }
    }

    // TODO Ne pas afficher l'utilisateur responsable
    public static function addVotant() : void {
        $idQuestion = $_GET['idQuestion'];
        $utilisateurs = (new UtilisateurRepository())->selectAll();
        $groupes = (new GroupeRepository())->selectAll();
        self::afficheVue('view.php',
            [
                "pagetitle" => "Ajouter un votant",
                "cheminVueBody" => "question/addVotant.php",
                "title" => "Ajouter un votant",
                "subtitle" => "Ajouter un ou plusieurs votants à la question",
                "idQuestion" => $idQuestion,
                "utilisateurs" => $utilisateurs,
                "groupes" => $groupes
            ]);
    }

    public static function addedVotant() : void {
        $idQuestion = $_POST['idQuestion'];
        foreach ($_POST['utilisateurs'] as $login) {
            $isOk = (new QuestionRepository())->ajouterVotant($idQuestion, $login);
        }

        if ($isOk) (new Notification())->ajouter("success", "Les votants ont été ajouté avec succès.");
        else (new Notification())->ajouter("warning", "Certains votants n'ont pas pu être ajouté.");
        self::redirection("?controller=question&action=readQuestion&&idQuestion=" . $idQuestion);
    }



    public static function updateQuestion() : void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if (!ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion()) == 'organisateur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        self::afficheVue('view.php',
            [
                 "pagetitle" => "Modifier une question",
                 "cheminVueBody" => "question/updateQuestion.php",
                 "title" => "Modifier une question",
                 "subtitle" => $question->getTitre(),
                 "question" => $question
            ]);
    }

    public static function updatedQuestion() : void {
        $question = (new QuestionRepository())->select($_POST['idQuestion']);
        if (!ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion()) == 'organisateur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action= all");
        }
        $isOk = (new QuestionRepository())->modifierQuestion($_POST['idQuestion'], $_POST['description'], 'visible');
        if ($isOk) (new Notification())->ajouter("success", "La question a été modifiée.");
        else (new Notification())->ajouter("warning", "La modification de la question a échoué.");
        self::redirection("?action=readQuestion&idQuestion=" . $_POST['idQuestion']);
    }

}



