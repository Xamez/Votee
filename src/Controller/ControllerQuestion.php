<?php

namespace App\Votee\Controller;

use App\Votee\Model\DataObject\Question;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerQuestion extends AbstractController {

    public static function created(): void {
        $question = new Question(NULL,
            $_POST['visibilite'],
            $_POST['titreQuestion'],
            $_POST['descriptionQuestion'],
            $_POST['dateDebutQuestion'],
            $_POST['dateFinQuestion'],
            $_POST['dateDebutVote'],
            $_POST['dateFinVote'],
            $_POST['login'],
        );
        (new QuestionRepository())->sauvegarder($question);
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Crée",
                "cheminVueBody" => "question/created.php",
                "title" => "Créer un vote",
                "subtitle" => "Remplissez les champs suivants pour réaliser votre enquête."
            ]);
    }

    public static function create(): void {
        $nbSections = $_POST['nbSections'];
        self::afficheVue('view.php',
            ["nbSections" => $nbSections,
                "pagetitle" => "Creation",
                "cheminVueBody" => "question/create.php",
                "title" => "Créer un vote",
                "subtitle" => ""
            ]);
    }

    public static function section(): void {
        self::afficheVue('view.php',
            ["pagetitle" => "Nombre de sections",
                "cheminVueBody" => "question/section.php",
                "title" => "Créer un vote",
                "subtitle" => "Définissez un nombre de section pour votre vote."
            ]);
    }

    public static function readAll(): void {
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "question/list.php",
                "title" => "Liste des votes",
                "subtitle" => ""]);
    }

    public static function read(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        $representant = (new UtilisateurRepository())->select($question->getLogin());
        if ($question) {
            self::afficheVue('view.php',
                ["question" => $question,
                    "sections" => $sections,
                    "representant" => $representant,
                    "pagetitle" => "Question",
                    "cheminVueBody" => "question/detail.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function error(string $errorMessage = "") {
        self::afficheVue("view.php",
            ["errorMessage" => $errorMessage,"pagetitle" => "Erreur", "cheminVueBody" => "question/error.php","title" => "",
                "subtitle" => ""]);
    }

    // -----------------------------
    // POUR TESTER LES PAGES LOGINS
    public static function connexion():void {
        self::afficheVue("view.php",
            ["pagetitle" => "Connexion", "cheminVueBody" => "login/connexion.php","title" => "Se connecter",
                "subtitle" => ""]);
    }
    public static function inscription():void {
        self::afficheVue("view.php",
            ["pagetitle" => "Inscription", "cheminVueBody" => "login/inscription.php","title" => "S'inscrire",
                "subtitle" => ""]);
    }
}

