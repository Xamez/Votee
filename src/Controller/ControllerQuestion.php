<?php

namespace App\Votee\Controller;

use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Question;
use App\Votee\Model\DataObject\Section;
use App\Votee\Model\DataObject\Texte;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\TexteRepository;
use App\Votee\Model\Repository\UtilisateurRepository;
use App\Votee\Model\Repository\VoteRepository;
use App\Votee\parsedown\Parsedown;

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
        self::afficheVue('view.php',
            [
                "pagetitle" => "Nombre de sections",
                "cheminVueBody" => "question/section.php",
                "title" => "Créer un vote",
                "subtitle" => "Définissez un nombre de section pour votre vote."
            ]);
    }

    public static function createQuestion(): void {
        $nbSections = $_POST['nbSections'];
        self::afficheVue('view.php',
            [
                "nbSections" => $nbSections,
                "pagetitle" => "Creation",
                "cheminVueBody" => "question/createQuestion.php",
                "title" => "Créer un vote",
            ]);
    }

    public static function readAllQuestion(): void {
        $questions = (new QuestionRepository())->selectAll();
        if (isset($questions)) {
            self::afficheVue('view.php',
                [
                    "questions" => $questions,
                    "pagetitle" => "Liste des questions",
                    "cheminVueBody" => "question/listQuestion.php",
                    "title" => "Liste des votes",
                ]);
        } else {
            self::error("Les questions n'ont pas été récupérées.");
        }
    }

    public static function readQuestion(): void {
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
                    ["question" => $question,
                        "notes" => $notes,
                        "propositions" => $propositions,
                        "responsables" => $responsables,
                        "idPropositionGagnante" => $idPropositionGagnante,
                        "sections" => $sections,
                        "organisateur" => $organisateur,
                        "pagetitle" => "Question",
                        "cheminVueBody" => "question/readQuestionResultats.php",
                        "title" => $question->getTitre(),
                        "subtitle" => $question->getDescription()]);
            } else {
                self::afficheVue('view.php',
                    ["question" => $question,
                        "propositions" => $propositions,
                        "sections" => $sections,
                        "organisateur" => $organisateur,
                        "responsables" => $responsables,
                        "pagetitle" => "Question",
                        "cheminVueBody" => "question/readQuestion.php",
                        "title" => $question->getTitre(),
                        "subtitle" => $question->getDescription()]);
            }
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdQuestion(): void {
        $question = new Question(NULL,
            $_POST['typeVote'],
            $_POST['visibilite'],
            $_POST['titreQuestion'],
            $_POST['descriptionQuestion'],
            date_format(date_create($_POST['dateDebutQuestion']), 'd/m/Y'),
            date_format(date_create($_POST['dateFinQuestion']), 'd/m/Y'),
            date_format(date_create($_POST['dateDebutVote']), 'd/m/Y'),
            date_format(date_create($_POST['dateFinVote']), 'd/m/Y'),
            $_POST['organisateur'],
        );
        var_dump($question);
        $idQuestion = (new QuestionRepository())->ajouterQuestion($question);
        $isOk = true;
        for ($i = 1; $i <= $_POST['nbSections'] && $isOk; $i++) {
            $section = new Section(NULL, $_POST['section' . $i], $idQuestion);
            $isOk = (new SectionRepository())->sauvegarder($section);
        }

        if ($isOk) (new Notification())->ajouter("success", "La question a été créée.");
        else (new Notification())->ajouter("warning", "L'ajout de la question a échoué.");
        self::redirection("?action=readAllQuestion");
    }

    public static function updateQuestion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        self::afficheVue('view.php',
            ["question" => $question,
                "pagetitle" => "Question",
                "cheminVueBody" => "question/updateQuestion.php",
                "title" => $question->getTitre(),
                "subtitle" => $question->getDescription()]);
    }

    public static function updatedQuestion(): void {
        $isOk = (new QuestionRepository())->modifierQuestion($_GET['idQuestion'], $_GET['description'], 'visible');
        if ($isOk) (new Notification())->ajouter("success", "La question a été modifiée.");
        else (new Notification())->ajouter("warning", "La modification de la question a échoué.");
        self::redirection("?action=readQuestion&idQuestion=" . $_GET['idQuestion']);
    }

    public static function selectFusion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        self::afficheVue('view.php',
            [
                "pagetitle" => "Créer la fusion",
                "cheminVueBody" => "question/selectFusion.php",
                "title" => $question->getTitre(),
                "subtitle" => $question->getDescription()
            ]);
    }

    public static function createFusion(): void {
        $question = (new QuestionRepository())->select($_POST['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_POST['idQuestion']);

        $textes1 = (new TexteRepository())->selectAllByKey($_POST['idProposition']);
        foreach ($textes1 as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }

        $textes2 = (new TexteRepository())->selectAllByKey($_POST['idProposition1']);
        foreach ($textes2 as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }

        $responsable1 = (new UtilisateurRepository())->selectResp($_POST['idProposition']);
        $responsable2 = (new UtilisateurRepository())->selectResp($_POST['idProposition1']);

        $coAuteurs1 = (new UtilisateurRepository())->selectCoAuteur($_POST['idProposition']);
        $coAuteurs2 = (new UtilisateurRepository())->selectCoAuteur($_POST['idProposition1']);
        $coAuteurs = array_unique(array_merge($coAuteurs1, $coAuteurs2), SORT_REGULAR);
        if (in_array($responsable1, $coAuteurs)) unset($coAuteurs[array_search($responsable1, $coAuteurs)]);
        self::afficheVue('view.php',
            [
                "pagetitle" => "Lire la fusion",
                "idPropositions" => array($_POST['idProposition'], $_POST['idProposition1']),
                "question" => $question,
                "sections" => $sections,
                "coAuteurs" => $coAuteurs,
                "textes" => array($textes1, $textes2),
                "responsable" => $responsable1, // Proposition header
                "responsables" => array($responsable1, $responsable2),
                "cheminVueBody" => "question/createFusion.php",
                "title" => $question->getTitre(),
                "subtitle" => $question->getDescription()
            ]);
    }

    public static function deletedCoAuteur(): void {
        $idProposition = $_GET['idProposition'];
        $login = $_GET['login'];
        if ((new PropositionRepository())->supprimerCoAuteur( $login, $idProposition)) {
            (new Notification())->ajouter("success", "Le co-auteur a été supprimé.");
        } else (new Notification())->ajouter("warning", "Le co-auteur n'a pas pu être supprimé.");
        self::redirection("?action=updateProposition&idQuestion=" . $_GET['idQuestion'] . "&idProposition=" . $_GET['idProposition']);
    }

    public static function createdCoAuteur():void {
        $idProposition = $_POST['idProposition'];
        $login = $_POST['login'];
        if ((new PropositionRepository())->ajouterCoAuteur( $login, $idProposition)) {
            (new Notification())->ajouter("success", "Le co-auteur a été ajouté.");
        } else (new Notification())->ajouter("warning", "Le co-auteur n'a pas pu être ajouté.");
        self::redirection("?action=updateProposition&idQuestion=" . $_POST['idQuestion'] . "&idProposition=" . $_POST['idProposition']);
    }


    public static function createdFusion(): void {
        (new PropositionRepository())->modifierProposition($_POST['idProposition1'], 'invisible');
        (new PropositionRepository())->modifierProposition($_POST['idProposition2'], 'invisible');
        $idProposition = (new PropositionRepository())->ajouterProposition('visible');
        $isOk = true;
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $texte = new Texte($_POST['idQuestion'], $_POST['idSection' . $i], $idProposition, $_POST['section' . $i], NULL);
            $isOk = (new TexteRepository())->sauvegarder($texte);
        }
        foreach ($_POST['coAuteurs'] as $coAuteur) {
            $isOk &= (new PropositionRepository())->ajouterCoauteur($coAuteur, $idProposition);
        }
        $isOk &= (new PropositionRepository())->ajouterRepresentant($_POST['responsable'], $idProposition, $_POST['idQuestion']);

        if ($isOk) (new Notification())->ajouter("success", "La fusion a été réalisée avec succès.");
        else {
            (new PropositionRepository())->supprimer($idProposition);
            (new PropositionRepository())->modifierProposition($_POST['idProposition1'], 'visible');
            (new PropositionRepository())->modifierProposition($_POST['idProposition2'], 'visible');
            (new Notification())->ajouter("danger", "La fusion n'a pas pu être réalisée.");
        }
        self::redirection("?action=readQuestion&idQuestion=" . $_POST['idQuestion']);
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



