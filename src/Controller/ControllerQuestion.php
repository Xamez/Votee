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
        if (ConnexionUtilisateur::estAdministrateur() || !ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
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
        if (ConnexionUtilisateur::estAdministrateur() || !ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une question !");
            self::redirection("?controller=question&all");
        }
        $nbSections = $_REQUEST['nbSections'];
        $voteTypes = VoteTypes::toArray();
        $users = (new UtilisateurRepository())->selectAll();
        $admins = UtilisateurRepository::getAdmins();
        $users = array_filter($users, function ($user) use ($admins) {
            return $user->getLogin() !== ConnexionUtilisateur::getUtilisateurConnecte()->getLogin() && !in_array($user->getLogin(), $admins);
        });
        self::afficheVue('view.php',
            [
                "nbSections" => $nbSections,
                "voteTypes" => $voteTypes,
                "pagetitle" => "Creation",
                "users" => $users,
                "cheminVueBody" => "question/createQuestion.php",
                "title" => "Créer une question",
            ]);
    }

    public static function all(): void {
        $search = $_GET['search'] ?? null;
        if ($search) $questions = (new QuestionRepository())->selectBySearch($search, 'TITRE');
        else $questions = (new QuestionRepository())->selectAll();
        foreach ($questions as $key=>$question) {
            if (!$question->isVisible()) {
                unset($questions[$key]);
                if ($question->getDateDebutQuestion() <= date('d/m/y')) {
                    $question->setVisibilite("visible");
                    //(new QuestionRepository())->modifier($question);
                }
            }
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "question/all.php",
                "title" => "Liste des questions",
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
                $idProposition = $proposition->getIdProposition();
                $responsables[$idProposition] = (new UtilisateurRepository())->selectResp($idProposition);
            }
            $votants = (new QuestionRepository())->selectVotant($_GET['idQuestion']);
            $groupesVotants = $groupes = (new GroupeRepository())->selectGroupeQuestion($_GET['idQuestion']);
            if (sizeof($groupes) < 10) {
                for ($i = 0; $i <  sizeof($votants) && $i < 10 - sizeof($groupes); $i++) {
                    $groupesVotants['votant' . $i] = $votants[$i];
                }
            }
            $organisateur = (new UtilisateurRepository())->select($question->getLogin());
            self::afficheVue('view.php',
                [
                    "question" => $question,
                    "propositions" => $propositions,
                    "sections" => $sections,
                    "organisateur" => $organisateur,
                    "responsables" => $responsables,
                    "groupesVotants" => $groupesVotants,
                    "size" => sizeof($votants) + sizeof($groupes),
                    "pagetitle" => "Question",
                    "cheminVueBody" => "question/readQuestion.php",
                    "title" => $question->getTitre(),
                    "subtitle" => $question->getDescription()
                ]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdQuestion(): void {
        if (ConnexionUtilisateur::estAdministrateur() || !ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
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
            $_POST['loginOrga'],
            $_POST['loginSpe'],
            $_POST['voteType']
        );

        if ((date_create($_POST['dateDebutQuestion']) > date_create($_POST['dateFinQuestion']))
            || (date_create($_POST['dateDebutVote']) > date_create($_POST['dateFinVote']))
            || (date_create($_POST['dateFinQuestion']) >= date_create($_POST['dateDebutVote']))) {
            (new Notification())->ajouter("warning", "Les dates sont incorrectes.");
            self::redirection("?action=controller=question&action=createQuestion&nbSections=" . $_POST['nbSections']);
        }

        $idQuestion = (new QuestionRepository())->sauvegarderSequence($question);
        $isOk = true;
        for ($i = 1; $i <= $_POST['nbSections'] && $isOk; $i++) {
            $section = new Section(NULL, $_POST['section' . $i], $idQuestion);
            $isOk = (new SectionRepository())->sauvegarder($section);
        }
        $loginSpe = $_POST['loginSpe'];
        if ($loginSpe != '-1') $isOk &= (new QuestionRepository())->ajouterSpecialiste($loginSpe);
        if ($idQuestion != NULL && $isOk) {
            (new Notification())->ajouter("success", "La question a été créée.");
            self::redirection("?controller=question&action=addVotant&idQuestion=$idQuestion");
        } else {
            if (!$isOk) (new QuestionRepository())->supprimer($idQuestion);
            ConnexionUtilisateur::ajouterScoreQuestion();
            (new Notification())->ajouter("warning", "L'ajout de la question a échoué.");
            self::redirection("?action=controller=question&action=createQuestion&nbSections=" . $_POST['nbSections']);
        }
    }

    public static function addVotant(): void {
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], ['Période d\'écriture', 'Période de préparation'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&all");
        }
        $question = (new QuestionRepository())->select($idQuestion);

        /* Recupère les administrateurs, l'organisateur, les representant et les coAuteurs de la question */
        $exception = (new UtilisateurRepository())->selectAllAdmins();
        $actors = (new UtilisateurRepository())->selectAllActorQuestion($idQuestion);
        $exception = array_merge($actors, $exception);

        $utilisateurs = (new UtilisateurRepository())->selectAll();

        $votants = (new QuestionRepository())->selectVotant($idQuestion);
        $votants = array_udiff($votants, array((new UtilisateurRepository())->select($question->getLogin())), function ($a, $b) {
            return $a->getLogin() <=> $b->getLogin();
        });
        if ($votants) $exception = array_merge($exception,$votants);

        $newUtilisateurs = array_udiff($utilisateurs, $exception, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });
        $groupesExistants = (new GroupeRepository())->selectGroupeQuestion($idQuestion);
        $groupes = (new GroupeRepository())->selectAll();
        $newGroupes = array_udiff($groupes, $groupesExistants, function ($a, $b) {
            return strcmp($a->getIdGroupe(), $b->getIdGroupe());
        });
        self::afficheVue('view.php',
            [
                "pagetitle" => "Ajouter un votant",
                "cheminVueBody" => "question/addVotant.php",
                "title" => "Ajouter un votant",
                "subtitle" => "Ajouter un ou plusieurs votants à la question",
                "actors" => $actors,
                "idQuestion" => $idQuestion,
                "newUtilisateurs" => $newUtilisateurs,
                "votants" => $votants,
                "groupes" => $groupesExistants,
                "newGroupes" => $newGroupes
            ]);
    }

    public static function addedVotant() : void {
        $idQuestion = $_POST['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], ['Période d\'écriture', 'Période de préparation'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&all");
        }

        /* Gestion des ajouts et suppression des votants */
        $oldVotants = (new QuestionRepository())->selectVotant($idQuestion);
        $votants = [];
        foreach ($oldVotants as $votant) $votants[] = $votant->getLogin();
        if (array_key_exists('votants', $_POST)) $votants = array_diff($votants, $_POST['votants']);
        $isOk = true;
        foreach ($_POST['utilisateurs'] as $login) {
            $isOk = (new QuestionRepository())->ajouterVotant($idQuestion, $login);
        }
        foreach ($votants as $login) {
            $isOk = (new QuestionRepository())->supprimerVotant($idQuestion, $login);
        }

        /* Gestion des ajouts et suppression des groupes */
        $oldGroupes = (new GroupeRepository())->selectGroupeQuestion($idQuestion);
        $groupes = [];
        foreach ($oldGroupes as $groupe) $groupes[] = $groupe->getIdGroupe();
        if (array_key_exists('groupesExist', $_POST)) $groupes = array_diff($groupes, $_POST['groupesExist']);
        foreach ($_POST['groupes'] as $idGroupe) {
            $isOk = (new GroupeRepository())->ajouterGroupeAQuestion($idQuestion, $idGroupe);
        }
        foreach ($groupes as $idGroupe) {
            $isOk = (new GroupeRepository())->supprimerGroupeDeQuestion($idQuestion, $idGroupe);
        }

        if ($isOk) (new Notification())->ajouter("success", "Les votants ont été ajouté avec succès.");
        else (new Notification())->ajouter("warning", "Certains votants n'ont pas pu être ajouté.");
        self::redirection("?controller=question&action=readQuestion&&idQuestion=$idQuestion");
    }



    public static function updateQuestion() : void {
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], ['Période d\'écriture', 'Période de préparation'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
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
        $idQuestion = $_POST['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'],['Période d\'écriture', 'Période de préparation'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
        $question->setVisibilite('visible');
        $question->setDescription($_POST['description']);
        $isOk = (new QuestionRepository())->modifier($question);
        if ($isOk) {
            (new Notification())->ajouter("success", "La question a été modifiée.");
            self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
        } else {
            (new Notification())->ajouter("warning", "La modification de la question a échoué.");
            self::redirection("?controller=question&action=updateQuestion&idQuestion=$idQuestion");
        }
    }

    public static function readVotant():void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=all");
        }
        $idQuestion = $_GET['idQuestion'];
        $question = (new QuestionRepository())->select($idQuestion);
        $groupes = (new GroupeRepository())->selectGroupeQuestion($idQuestion);
        $votants = (new QuestionRepository())->selectVotant($idQuestion);
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des votants",
                "cheminVueBody" => "question/readVotant.php",
                "title" => "Liste des votants",
                "subtitle" => $question->getTitre(),
                "question" => $question,
                "groupes" => $groupes,
                "votants" => $votants
            ]);
    }

    /** Retourne true si la question est en phase d'ecriture et si l'utilisateur a les roles requis */
    public static function hasPermission($idQuestion, $rolesArray, $periodeArray): bool {
        $question = (new QuestionRepository())->select($idQuestion);
        $roles = ConnexionUtilisateur::getRolesQuestion($idQuestion);
        return in_array($question->getPeriodeActuelle(), $periodeArray) && (count(array_intersect($rolesArray, $roles)) > 0);
    }

}



