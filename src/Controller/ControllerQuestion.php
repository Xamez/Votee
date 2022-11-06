<?php

namespace App\Votee\Controller;

use App\Votee\Model\DataObject\Question;
use App\Votee\Model\DataObject\Section;
use App\Votee\Model\DataObject\Texte;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\TexteRepository;
use App\Votee\Model\Repository\UtilisateurRepository;

class ControllerQuestion extends AbstractController {

    public static function home(): void {
        self::afficheVue('view.php',
            ["pagetitle" => "Page d'accueil",
                "mainType" => 1,
                "footerType" => 1,
                "cheminVueBody" => "home.php"
            ]);
    }

    public static function section(): void {
        self::afficheVue('view.php',
            ["pagetitle" => "Nombre de sections",
                "cheminVueBody" => "organisateur/section.php",
                "title" => "Créer un vote",
                "subtitle" => "Définissez un nombre de section pour votre vote."
            ]);
    }

    public static function create(): void {
        $nbSections = $_POST['nbSections'];
        self::afficheVue('view.php',
            ["nbSections" => $nbSections,
             "pagetitle" => "Creation",
             "cheminVueBody" => "organisateur/create.php",
             "title" => "Créer un vote",
             "subtitle" => ""
            ]);
    }

    public static function readAll(): void {
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "organisateur/list.php",
                "title" => "Liste des votes",
                "subtitle" => ""]);
    }

    public static function read(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
        $responsables = array();
        foreach ($propositions as $proposition) {
            $responsables[] = (new UtilisateurRepository())->selectResp($proposition->getIdProposition());
        }
        if ($question) {
            $organisateur = (new UtilisateurRepository())->select($question->getLogin());
            self::afficheVue('view.php',
                ["question" => $question,
                    "propositions" => $propositions,
                    "sections" => $sections,
                    "organisateur" => $organisateur,
                    "responsables" => $responsables,
                    "pagetitle" => "Question",
                    "cheminVueBody" => "organisateur/readQuestion.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function created(): void {
        $question = new Question(NULL,
            $_POST['visibilite'],
            $_POST['titreQuestion'],
            $_POST['descriptionQuestion'],
            date_format(date_create($_POST['dateDebutQuestion']),'d/m/Y'),
            date_format(date_create($_POST['dateFinQuestion']),'d/m/Y'),
            date_format(date_create($_POST['dateDebutVote']),'d/m/Y'),
            date_format(date_create($_POST['dateFinVote']),'d/m/Y'),
            $_POST['login'],
        );
        (new QuestionRepository())->sauvegarder($question);
        $question = (new QuestionRepository())->selectByMultiKey(array("titre"=> $_POST['titreQuestion'],"description"=> $_POST['descriptionQuestion']));
        $idQuestion = $question->getIdQuestion();
        for ($i = 1; $i <= $_POST['nbSections']; $i++) {
            $section = new Section(
                NULL,
                $_POST['section'.$i],
                $idQuestion
            );
            (new SectionRepository())->sauvegarder($section);
        }
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Crée",
                "cheminVueBody" => "organisateur/created.php",
                "title" => "Créer un vote",
                "subtitle" => "Remplissez les champs suivants pour réaliser votre enquête."
            ]);
    }

    public static function createProposition(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        if ($question) {
            self::afficheVue('view.php',
                [   "pagetitle" => "Creation",
                    "sections" => $sections,
                    "cheminVueBody" => "organisateur/createProposition.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()
                ]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdProposition(): void {
        $idProposition = (new PropositionRepository())->ajouterProposition();
        for ($i = 0; $i < $_POST['nbSections']; $i++) {
            $texte = new Texte(
                $_POST['idSection'.$i],
                $idProposition,
                $_POST['section'.$i],
            );
            (new TexteRepository())->sauvegarder($texte);
        }
    }

    public static function propositionHeader(): void {
        $responsable = (new UtilisateurRepository())->selectResp($_GET['idProposition']);
        $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']);
        self::afficheVue('view.php',
            ["responsable" => $responsable,
                "coAuteurs" => $coAuteurs,
                "pagetitle" => "Suppression",
                "cheminVueBody" => "organisateur/deleteProposition.php",
                "title" => "Supression d'un vote",
                "subtitle" => ""]);
    }

    public static function update(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        $responsable = (new UtilisateurRepository())->selectResp($_GET['idProposition']);
        $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']);
        $textes = (new TexteRepository())->selectAllByKey($_GET['idProposition']);
        self::afficheVue('view.php',
            ["question" => $question,
                "idProposition" => $_GET['idProposition'],
                "sections" => $sections,
                "coAuteurs" => $coAuteurs,
                "textes" => $textes,
                "responsable" => $responsable,
                "pagetitle" => "Edition de proposition",
                "cheminVueBody" => "organisateur/updateProposition.php",
                "title" => $question->getTitre(),
                "subtitle" => $question->getDescription()]);
    }

    public static function updated(): void {
        for ($i = 0; $i < $_GET['nbSections']; $i++) {
            $texte = new Texte(
                $_GET['idSection'.$i],
                $_GET['idProposition'],
                $_GET['section'.$i]
            );
            (new TexteRepository())->modifier($texte);
        }
        self::afficheVue('view.php',
            ["title" => "Modifié",
             "cheminVueBody" => "organisateur/updated.php",
             "subtitle" => ""]
        );
    }

    public static function proposition(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $textes = (new TexteRepository())->selectAllByKey($_GET['idProposition']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        if ($question) {
            $responsable = (new UtilisateurRepository())->selectResp($_GET['idProposition']);
            $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']);
            self::afficheVue('view.php',
                ["question" => $question,
                 "idProposition" => $_GET['idProposition'],
                 "sections" => $sections,
                 "coAuteurs" => $coAuteurs,
                 "textes" => $textes,
                 "responsable" => $responsable,
                 "pagetitle" => "Question",
                 "cheminVueBody" => "organisateur/readProposition.php",
                 "title" => $question->getTitre(),
                 "subtitle" => $question->getDescription()]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function deleteProposition(): void {
        $idProposition = $_GET['idProposition'];
        self::afficheVue('view.php',
            ["idProposition" => $idProposition,
             "pagetitle" => "Confirmation",
             "cheminVueBody" => "organisateur/deleteProposition.php",
             "title" => "Confirmation de suppression",
             "subtitle" => ""]);
    }

    public static function deletedProposition(): void {
        $idProposition = $_GET['idProposition'];
        (new PropositionRepository())->supprimer($idProposition);
        self::afficheVue('view.php',
            ["pagetitle" => "Supprimée",
             "cheminVueBody" => "organisateur/deletedProposition.php",
             "title" => "Proposition supprimée !",
             "subtitle" => ""
            ]);
    }

    public static function error(string $errorMessage = "") {
        self::afficheVue("view.php",
            ["errorMessage" => $errorMessage,"pagetitle" => "Erreur", "cheminVueBody" => "organisateur/error.php","title" => "",
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

