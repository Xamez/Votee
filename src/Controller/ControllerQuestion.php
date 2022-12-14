<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Question;
use App\Votee\Model\DataObject\Section;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;
use App\Votee\Model\Repository\VoteRepository;

class ControllerQuestion extends AbstractController {

    public static function home(): void {
        self::afficheVue('view.php',
            [
                "pagetitle" => "Page d'accueil",
                "mainType" => 1,
                "footerType" => 1,
                "cheminVueBody" => "home.php"
            ]);
    }

    public static function section(): void {
        if (!ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer un vote !");
            self::redirection("?controller=question&all");
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Nombre de sections",
                "cheminVueBody" => "question/section.php",
                "title" => "Créer un vote",
                "subtitle" => "Définissez un nombre de section pour votre vote."
            ]);
    }

    public static function createQuestion(): void {
        if (!ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer un vote !");
            self::redirection("?controller=question&all");
        }
        $nbSections = $_POST['nbSections'];
        self::afficheVue('view.php',
            [
                "nbSections" => $nbSections,
                "pagetitle" => "Creation",
                "cheminVueBody" => "question/createQuestion.php",
                "title" => "Créer un vote",
            ]);
    }

    // Permet de voir toutes les questions du site
    public static function all(): void {
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des votes",
                "cheminVueBody" => "question/all.php",
                "title" => "Liste des votes",
                "questions" => $questions
            ]);
    }

    public static function readQuestion(): void {
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
                $responsables[] = (new UtilisateurRepository())->selectResp($proposition->getIdProposition());
            }
            $organisateur = (new UtilisateurRepository())->select($question->getLogin());
            if($question->getPeriodeActuelle() == "Période des résultats"){
                $idPropositionGagnante = (new PropositionRepository())->selectGagnant($question->getIdQuestion());
                $notes = array();
                foreach ($propositions as $proposition) {
                    $notes[] = (new PropositionRepository())->getNote($proposition->getIdProposition());
                }
                self::afficheVue('view.php',
                    [
                        "question" => $question,
                        "notes" => $notes,
                        "propositions" => $propositions,
                        "responsables" => $responsables,
                        "idPropositionGagnante" => $idPropositionGagnante,
                        "sections" => $sections,
                        "organisateur" => $organisateur,
                        "pagetitle" => "Question",
                        "cheminVueBody" => "question/readQuestionResultats.php",
                        "title" => $question->getTitre(),
                        "subtitle" => $question->getDescription()
                    ]);
            } else {
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
            }
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdQuestion(): void {
        if (!ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer un vote !");
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
            $_POST['typeVote'],
        );
        $idQuestion = (new QuestionRepository())->ajouterQuestion($question);
        $isOk = true;
        for ($i = 1; $i <= $_POST['nbSections'] && $isOk; $i++) {
            $section = new Section(NULL, $_POST['section' . $i], $idQuestion);
            $isOk = (new SectionRepository())->sauvegarder($section);
        }
        if ($isOk) (new Notification())->ajouter("success", "La question a été créée.");
        else {
            (new QuestionRepository())->supprimer($idQuestion);
            (new Notification())->ajouter("warning", "L'ajout de la question a échoué.");
            self::redirection("?action=all");
        }
        self::redirection("?action=all");
    }

    public static function updateQuestion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if (!ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'organisateur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        self::afficheVue('view.php',
            [
                "question" => $question,
                "pagetitle" => "Question",
                "cheminVueBody" => "question/updateQuestion.php",
                "title" => $question->getTitre(),
                "subtitle" => $question->getDescription()
            ]);
    }

    public static function updatedQuestion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if (!ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'organisateur') {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action= all");
        }
        $isOk = (new QuestionRepository())->modifierQuestion($_GET['idQuestion'], $_GET['description'], 'visible');
        if ($isOk) (new Notification())->ajouter("success", "La question a été modifiée.");
        else (new Notification())->ajouter("warning", "La modification de la question a échoué.");
        self::redirection("?action=readQuestion&idQuestion=" . $_GET['idQuestion']);
    }




    public static function createVote(){
        $vote = (new VoteRepository())->ajouterVote($_GET['idProposition'],'votant3',$_GET['value']); // STUB
        if ($vote) {
            (new Notification())->ajouter("success", "Le vote a bien été effectué.");
        }
        else{
            (new Notification())->ajouter("warning", "Le vote existe déjà.");
        }
        self::redirection("?action=readQuestion&idQuestion=" . $_GET['idQuestion']);
    }

}



