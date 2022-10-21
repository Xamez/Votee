<?php

namespace App\Votee\Controller;

use App\Votee\Model\DataObject\Question;
use App\Votee\Model\Repository\QuestionRepository;

class ControllerQuestion extends AbstractController {

    public static function created(): void {
        $question = new Question(NULL,
            $_POST['visibilite'],
            $_POST['systemeVoteQuestion'],
            $_POST['planTexteQuestion'],
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
            ["questions" => $questions,"pagetitle" => "Crée", "cheminVueBody" => "question/created.php"]);
    }

    public static function create(): void {
        self::afficheVue('view.php',
            ["pagetitle" => "Creation", "cheminVueBody" => "question/create.php"]);
    }

    public static function readAll(): void {
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            ["questions" => $questions,"pagetitle" => "Liste des questions", "cheminVueBody" => "question/list.php"]);
    }

    public static function read(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if ($question) {
            self::afficheVue('view.php',
                ["question" => $question, "pagetitle" => "Question", "cheminVueBody" => "question/detail.php"]);
        } else {
            self::error("La voiture n'existe pas");
        }
    }

    public static function error(string $errorMessage = "") {
        self::afficheVue("view.php",
            ["errorMessage" => $errorMessage,"pagetitle" => "Erreur", "cheminVueBody" => "question/error.php"]);
    }
}

