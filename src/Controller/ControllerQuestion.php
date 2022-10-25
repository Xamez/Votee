<?php

namespace App\Votee\Controller;

use App\Votee\Model\DataObject\Question;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;

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
            $_POST['idCategorie'],
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
        self::afficheVue('view.php',
            ["pagetitle" => "Creation",
                "cheminVueBody" => "question/create.php",
                "title" => "Créer un vote",
                "subtitle" => ""
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
        if ($question) {
            self::afficheVue('view.php',
                ["question" => $question,
                    "sections" => $sections,
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
}

