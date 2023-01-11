<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\MotDePasse;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Periodes;
use App\Votee\Model\DataObject\Question;
use App\Votee\Model\DataObject\Section;
use App\Votee\Model\DataObject\VoteTypes;
use App\Votee\Model\Repository\DemandeRepository;
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
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une question.");
            self::redirection("?controller=question&action=all");
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
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une question.");
            self::redirection("?controller=question&action=all");
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
        $periode = $_GET['periode'] ?? null;

        // filtre search
        if ($search) $questions = (new QuestionRepository())->selectBySearch($search, 'TITRE');
        else $questions = (new QuestionRepository())->selectAll();

        // filtre periode
        if ($periode) {
            $questions = array_filter($questions, function ($question) use ($periode) {
                return $question->getPeriodeActuelle() === $periode;
            });
        }

        // Si une question est en preparation, elle devient visible pour les utilisateurs automatiquement
        foreach ($questions as $key=>$question) {
            if (!$question->isVisible()) {
                unset($questions[$key]);
                if ($question->getDateDebutQuestion() <= strtotime("now")) {
                    $question->setVisibilite("visible");
                    (new QuestionRepository())->modifier($question);
                }
            }
        }
        $demandesCours = [];
        if (ConnexionUtilisateur::estConnecte()) $demandesCours = (new DemandeRepository())->selectAllByMultiKey(['login' => ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(),
                'TITREDEMANDE' => 'question', 'ETATDEMANDE' => 'attente']);
        $isDemande = sizeof($demandesCours) > 0;
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "question/all.php",
                "title" => "Liste des questions",
                "questions" => $questions,
                "isDemande" => $isDemande
            ]);
    }

    public static function readQuestion(): void {
        self::redirectConnexion("?controller=utilisateur&action=connexion");
        $idQuestion = $_GET['idQuestion'];
        $question = (new QuestionRepository())->select($idQuestion);
        if ($question) {
            $sections = (new SectionRepository())->selectAllByKey($idQuestion);
            $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$idQuestion));
            $responsables = array();
            foreach ($propositions as $proposition) {
                $idProposition = $proposition->getIdProposition();
                $responsables[$idProposition] = (new UtilisateurRepository())->selectResp($idProposition);
            }
            $votants = (new QuestionRepository())->selectVotant($idQuestion);
            $groupesVotants = $groupes = (new GroupeRepository())->selectGroupeQuestion($idQuestion);
            if (sizeof($groupes) < 10) {
                for ($i = 0; $i <  sizeof($votants) && $i < 10 - sizeof($groupes); $i++) {
                    $groupesVotants['votant' . $i] = $votants[$i];
                }
            }
            $organisateur = (new UtilisateurRepository())->select($question->getLogin());
            $specialiste = (new UtilisateurRepository())->select($question->getLoginSpecialiste());
            $demandesCours = (new DemandeRepository())->selectAllByMultiKey(['login' => ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(),
                    'TITREDEMANDE' => 'proposition', 'ETATDEMANDE' => 'attente', 'idQuestion' => $idQuestion]);
            $isDemande = sizeof($demandesCours) > 0;
            self::afficheVue('view.php',
                [
                    "question" => $question,
                    "propositions" => $propositions,
                    "sections" => $sections,
                    "organisateur" => $organisateur,
                    "responsables" => $responsables,
                    "specialiste" => $specialiste,
                    "groupesVotants" => $groupesVotants,
                    "isDemande" => $isDemande,
                    "size" => sizeof($votants) + sizeof($groupes),
                    "pagetitle" => "Question",
                    "cheminVueBody" => "question/readQuestion.php",
                    "title" => $question->getTitre()
                ]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdQuestion(): void {
        if (ConnexionUtilisateur::estAdministrateur() || !ConnexionUtilisateur::estConnecte() || !ConnexionUtilisateur::creerQuestion()) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une question.");
            self::redirection("?controller=question&action=all");
        }
        $question = new Question(NULL,
            ($_POST['dateDebutQuestion'] > date('Y-m-d') ? 'invisible' : 'visible'),
            $_POST['titreQuestion'],
            $_POST['descriptionQuestion'],
            date_format(date_create($_POST['dateDebutQuestion']), 'd/m/y 00:00:00'),
            date_format(date_create($_POST['dateFinQuestion']), 'd/m/y 23:59:59'),
            date_format(date_create($_POST['dateDebutVote']), 'd/m/y 23:59:59'),
            date_format(date_create($_POST['dateFinVote']), 'd/m/y 23:59:59'),
            $_POST['loginOrga'],
            $_POST['loginSpe'],
            $_POST['voteType']
        );

        if ((date_create($_POST['dateDebutQuestion']) > date_create($_POST['dateFinQuestion']))
            || (date_create($_POST['dateDebutVote']) > date_create($_POST['dateFinVote']))
            || (date_create($_POST['dateFinQuestion']) > date_create($_POST['dateDebutVote']))
            || (date_create($_POST['dateFinQuestion']) == date_create($_POST['dateDebutQuestion']))
            || (date_create($_POST['dateFinVote']) == date_create($_POST['dateDebutVote']))) {
            (new Notification())->ajouter("warning", "Les dates sont incorrectes.");
            self::redirection("?action=controller=question&action=createQuestion&nbSections=" . $_POST['nbSections']);
        }

        $isOk = true;

        $loginSpe = $_POST['loginSpe'];
        if ($loginSpe == '') $question->setLoginSpecialiste(NULL);

        $idQuestion = (new QuestionRepository())->sauvegarderSequence($question);

        if ($loginSpe != '') $isOk &= (new QuestionRepository())->ajouterSpecialiste($loginSpe); // on appel la procédure après la création de la question par précaution

        foreach ($_POST['sections'] as $key=>$section) {
            $section = new Section(NULL, $section, $idQuestion, $_POST['descriptionsSection'][$key]);
            $isOk = (new SectionRepository())->sauvegarder($section);
        }
        if ($idQuestion != NULL && $isOk) {
            (new Notification())->ajouter("success", "La question a été créée.");
            self::redirection("?controller=question&action=addResp&type=create&idQuestion=$idQuestion");
        } else {
            if (!$isOk) (new QuestionRepository())->supprimer($idQuestion);
            ConnexionUtilisateur::ajouterScoreQuestion();
            (new Notification())->ajouter("warning", "L'ajout de la question a échoué.");
            self::redirection("?action=controller=question&action=createQuestion&nbSections=" . $_POST['nbSections']);
        }
    }

    public static function addResp(): void {
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);

        $admins = (new UtilisateurRepository())->selectAllAdmins();
        $organisateur = (new UtilisateurRepository())->select($question->getLogin());
        /* Responsables actuels de la question */
        $responsables = (new UtilisateurRepository())->selectRespQuestion($idQuestion);

        /* Liste de tous les utilisateurs de la base de donnée */
        $utilisateurs = (new UtilisateurRepository())->selectAll();

        /* Liste des utilisateurs qui peuvent créer une proposition => devenir responsable (permissions) */
        $responsablesPossibles = (new UtilisateurRepository())->selectProchainResp($idQuestion);

        $exeptions = $admins;
        $exeptions[] = $organisateur;
        $exeptions = array_merge($exeptions,$responsablesPossibles);
        $exeptions = array_merge($exeptions,$responsables);

        /* Utilisateurs sans les responsables actuels */
        $newUtilisateurs = array_udiff($utilisateurs, $exeptions, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });
        self::afficheVue('view.php',
            [
                "pagetitle" => "Ajouter des responsables",
                "cheminVueBody" => "question/addResp.php",
                "title" => "Ajouter des responsables",
                "subtitle" => "Ajouter un ou plusieurs responsables à la question",
                "responsables" => $responsables,
                "responsablesPossibles" => $responsablesPossibles,
                "utilisateurs" => $newUtilisateurs,
                "idQuestion" => $idQuestion,
                "typeRedi" => array_key_exists('type', $_GET) ? $_GET['type'] : null
            ]);
    }

    public static function addedResp(): void {
        $idQuestion = $_POST['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }

        /* Gestion des ajouts et suppression des représentants */
        $oldResp = (new UtilisateurRepository())->selectProchainResp($idQuestion);
        $responsables = [];
        foreach ($oldResp as $resp) $responsables[] = $resp->getLogin();
        if (array_key_exists('resps', $_POST)) $responsables = array_diff($responsables, $_POST['resps']);
        $isOk = true;
        if (isset($_POST['utilisateurs'])) {
            foreach ($_POST['utilisateurs'] as $login) {
                $isOk &= (new PropositionRepository())->ajouterScoreProposition($login, $idQuestion);
                $isOk &= (new QuestionRepository())->ajouterVotant($idQuestion, $login);
            }
        }
        foreach ($responsables as $login) {
            $isOk &= (new PropositionRepository())->enleverScoreProposition($login, $idQuestion);
            $isOk &= (new QuestionRepository())->supprimerVotant($idQuestion, $login);
        }

        if ($isOk) {
            (new Notification())->ajouter("success", "Les responsables ont été ajouté avec succès.");
            if ($_POST['type'] == 'create') self::redirection("?controller=question&action=addVotant&idQuestion=$idQuestion");
            else self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
        } else {
            (new Notification())->ajouter("warning", "Certains responsables n'ont pas pu être ajouté.");
            self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
        }
    }

    public static function addVotant(): void {
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        /* Récupère les administrateurs, l'organisateur, les représentants et les coAuteurs de la question */
        $admins = (new UtilisateurRepository())->selectAllAdmins();
        $actors = (new UtilisateurRepository())->selectAllActorQuestion($idQuestion);
        $allActors = array_merge($actors, $admins);

        $allUtilisateurs = (new UtilisateurRepository())->selectAll();

        /* Supprime les acteurs de la question de la liste des futurs votants */
        $utilWithoutActors = array_udiff($allUtilisateurs, $allActors, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });

        /* Supprimes les acteurs de la question de la liste des votants actuels */
        $allVotants = (new QuestionRepository())->selectVotant($idQuestion);
        $votants = array_udiff($allVotants, $allActors, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });

        /* Supprime les votants actuels de la question de la liste des futurs votants */
        $newUtilisateurs = array_udiff($utilWithoutActors, $allVotants, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });

        /* Récupère les groupes et scinde ceux qui sont déjà attribués à la question et ceux qui ne le sont pas */
        $groupesExistants = (new GroupeRepository())->selectGroupeQuestion($idQuestion);
        $groupes = (new GroupeRepository())->selectAll();
        $newGroupes = array_udiff($groupes, $groupesExistants, function ($a, $b) {
            return strcmp($a->getIdGroupe(), $b->getIdGroupe());
        });
        self::afficheVue('view.php',
            [
                "pagetitle" => "Ajouter des votants",
                "cheminVueBody" => "question/addVotant.php",
                "title" => "Ajouter des votants",
                "subtitle" => "Ajouter un ou plusieurs votants à la question",
                "idQuestion" => $idQuestion,
                "actors" => $actors,
                "votants" => $votants,
                "newUtilisateurs" => $newUtilisateurs,
                "groupes" => $groupesExistants,
                "newGroupes" => $newGroupes
            ]);
    }

    public static function addedVotant() : void {
        $idQuestion = $_POST['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }

        /* Gestion des ajouts et suppression des votants */
        $oldVotants = (new QuestionRepository())->selectVotant($idQuestion);
        $votants = [];
        foreach ($oldVotants as $votant) $votants[] = $votant->getLogin();
        if (array_key_exists('votants', $_POST)) $votants = array_diff($votants, $_POST['votants']);
        $isOk = true;
        if (isset($_POST['utilisateurs'])) {
            foreach ($_POST['utilisateurs'] as $login) {
                $isOk = (new QuestionRepository())->ajouterVotant($idQuestion, $login);
            }
        }
        foreach ($votants as $login) {
            $isOk = (new QuestionRepository())->supprimerVotant($idQuestion, $login);
        }

        /* Gestion des ajouts et suppression des groupes */
        $oldGroupes = (new GroupeRepository())->selectGroupeQuestion($idQuestion);
        $groupes = [];
        foreach ($oldGroupes as $groupe) $groupes[] = $groupe->getIdGroupe();
        if (array_key_exists('groupesExist', $_POST)) $groupes = array_diff($groupes, $_POST['groupesExist']);
        if (isset($_POST['groupes'])) {
            foreach ($_POST['groupes'] as $idGroupe) {
                $isOk = (new GroupeRepository())->ajouterGroupeAQuestion($idQuestion, $idGroupe);
            }
        }
        foreach ($groupes as $idGroupe) {
            $isOk = (new GroupeRepository())->supprimerGroupeDeQuestion($idQuestion, $idGroupe);
        }

        if ($isOk) (new Notification())->ajouter("success", "Les votants ont été ajouté avec succès.");
        else (new Notification())->ajouter("warning", "Certains votants n'ont pas pu être ajouté.");
        self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
    }



    public static function updateQuestion() : void {
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
        self::afficheVue('view.php',
            [
                 "pagetitle" => "Modifier une question",
                 "cheminVueBody" => "question/updateQuestion.php",
                 "title" => "Modifier la question",
                 "question" => $question
            ]);
    }

    public static function updatedQuestion() : void {
        $idQuestion = $_POST['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'],[Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
        $question->setVisibilite('visible');
        $question->setDescription($_POST['description']);
        $isOk = (new QuestionRepository())->modifier($question);
        if ($isOk) {
            (new Notification())->ajouter("success", "La question a été modifiée.");
            self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
        } else {
            (new Notification())->ajouter("warning", "La modification a échoué.");
            self::redirection("?controller=question&action=updateQuestion&idQuestion=$idQuestion");
        }
    }

    public static function deleteQuestion() : void {
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Suppressions d'une question",
                "cheminVueBody" => "question/deleteQuestion.php",
                "title" => "Supprimer la question",
                "idQuestion" => $idQuestion
            ]);
    }

    public static function deletedQuestion() : void {
        $idQuestion = $_POST['idQuestion'];
        $motDePasse = $_POST['motDePasse'];
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        if (!self::hasPermission($idQuestion, ['Organisateur'], [Periodes::ECRITURE->value, Periodes::PREPARATION->value])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        if (!MotDePasse::verifier($motDePasse, $utilisateur->getMotDePasse())) {
            (new Notification())->ajouter("warning", "Mot de passe incorrect.");
            self::redirection("?controller=question&action=deleteQuestion&idQuestion=$idQuestion");
        }
        $isOk = (new QuestionRepository())->supprimer($idQuestion);
        if ($isOk) {
            (new Notification())->ajouter("success", "La question a été supprimée.");
            self::redirection("?controller=question&action=all");
        } else {
            (new Notification())->ajouter("warning", "La suppression a échoué.");
            self::redirection("?controller=question&action=deleteQuestion&idQuestion=$idQuestion");
        }
    }

    public static function readVotant():void {
        self::redirectConnexion("?controller=utilisateur&action=connexion");
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

    public static function changePhase():void {
        $idQuestion = $_GET['idQuestion'];
        if (!in_array('Organisateur', ConnexionUtilisateur::getRolesQuestion($idQuestion))) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
        $now = strtotime("now") - 1;
        $today = strtotime("today");
        $debutEcriture = $question->getDateDebutQuestion();
        $finEcriture = $question->getDateFinQuestion();
        $debutVote = $question->getDateDebutVote();
        $finVote = $question->getDateFinVote();

        if (in_array($today, [strtotime(date("Y-m-d", $debutEcriture)), strtotime(date("Y-m-d", $finEcriture)), strtotime(date("Y-m-d", $debutVote)), strtotime(date("Y-m-d", $finVote))])) {

            if ($now < $debutEcriture && $today == strtotime(date("Y-m-d", $debutEcriture))) $question->setDateDebutQuestion($now);
            elseif ($now < $finEcriture && $today == strtotime(date("Y-m-d", $finEcriture))) $question->setDateFinQuestion($now);
            elseif ($now < $debutVote && $today == strtotime(date("Y-m-d", $debutVote))) $question->setDateDebutVote($now);
            elseif ($now < $finVote && $today == strtotime(date("Y-m-d", $finVote))) $question->setDateFinVote($now);

            $isOk = (new QuestionRepository())->modifierHeureQuestion($question);
            if ($question->getPeriodeActuelle() == Periodes::TRANSITION->value && $now < $debutVote && $today == strtotime(date("Y-m-d", $debutVote))) self::changePhase();

            if ($isOk) (new Notification())->ajouter("success", "La phase de la question a été modifiée.");
            else (new Notification())->ajouter("warning", "La modification de la phase a échoué.");

            self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
        } else {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
    }

    /** Retourne true si la question est en phase d'ecriture et si l'utilisateur a les roles requis */
    public static function hasPermission($idQuestion, $rolesArray, $periodesArray): bool {
        $question = (new QuestionRepository())->select($idQuestion);
        $roles = ConnexionUtilisateur::getRolesQuestion($idQuestion);
        return in_array($question->getPeriodeActuelle(), $periodesArray) && (count(array_intersect($rolesArray, $roles)) > 0);
    }

}
