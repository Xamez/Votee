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
use App\Votee\Model\Repository\VoteRepository;
use App\Votee\parsedown\Parsedown;

class ControllerProposition extends AbstractController {

    public static function createVote($idQuestion, $idVotant, $idProposition, $isRedirected) : void {
        $note = (new VoteRepository())->getNote($idProposition, $idVotant);
        $vote = (new VoteRepository())->construire(["idProposition" => $idProposition, "loginVotant" => $idVotant, "noteProposition" => 0]);
        $voteType = $vote->getVoteType()->name;
        $voteType = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($voteType))));
        $voteType = strtolower(substr($voteType, 0, 1)) . substr($voteType, 1);
        $voteUrl = 'proposition/vote/' . $voteType . '.php';
        self::afficheVue($voteUrl,
            [
                "idQuestion" => $idQuestion,
                "idVotant" => $idVotant,
                "idProposition" => $idProposition,
                "note" => $note,
                "isRedirected" => $isRedirected
            ]);
    }

    public static function createdVote() : void {
        $roles = ConnexionUtilisateur::getRolesProposition($_POST['idProposition']);
        if (!(count(array_intersect(['Responsable', 'Organisateur','Votant'], $roles)) > 0)) {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&readAllQuestion");
        } else {
            $vote = (new VoteRepository())->ajouterVote($_POST['idProposition'], $_POST['idVotant'], $_POST['noteProposition']);
            if ($vote)
                (new Notification())->ajouter("success", "Le vote a bien été effectué.");
            else
                (new Notification())->ajouter("warning", "Le vote existe déjà.");
            if ($_POST["isRedirected"] ?? false)
                self::redirection("?controller=proposition&action=readProposition&idQuestion=" . $_POST['idQuestion'] . "&idProposition=" . $_POST['idProposition']);
            else
                self::redirection("?controller=proposition&action=voterPropositions&idQuestion=" . $_POST['idQuestion']);
        }
    }

    public static function voterPropositions() : void {
        $idVotant = ConnexionUtilisateur::getUtilisateurConnecte()->getLogin();
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
        foreach ($propositions as $proposition) {
            $idProposition = $proposition->getIdProposition();
            $responsables[$idProposition] = (new UtilisateurRepository())->selectResp($idProposition);
            $textess = (new TexteRepository())->selectAllByKey($idProposition);
            $textes[$idProposition] = $textess;
            $aVote[$idProposition] = (new VoteRepository())->getNote($idProposition, $idVotant) != 0;
            foreach ($textess as $texte) {
                $parsedown = new Parsedown();
                $texte->setTexte($parsedown->text($texte->getTexte()));
            }
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Voter",
                "cheminVueBody" => "proposition/voterPropositions.php",
                "title" => "Voter",
                "subtitle" => "Voter pour les propositions de la question : " . $question->getTitre(),
                "propositions" => $propositions,
                "sections" => $sections,
                "textes" => $textes,
                "responsables" => $responsables,
                "aVote" => $aVote,
                "idQuestion" => $_GET['idQuestion'],
            ]);
    }

    public static function resultatPropositions() : void {

        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $voteType = $question->getVoteType();
        $voteType = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($voteType))));
        $voteType = strtolower(substr($voteType, 0, 1)) . substr($voteType, 1);
        $voteUrl = 'proposition/vote/resultat/' . $voteType . '.php';

        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
        foreach ($propositions as $proposition) {
            $idProposition = $proposition->getIdProposition();
            $responsables[$idProposition] = (new UtilisateurRepository())->selectResp($idProposition);
            $textess = (new TexteRepository())->selectAllByKey($idProposition);
            $textes[$idProposition] = $textess;
            $resultats[$idProposition] = (new VoteRepository())->getGetResultats($question->getIdQuestion());
            foreach ($textess as $texte) {
                $parsedown = new Parsedown();
                $texte->setTexte($parsedown->text($texte->getTexte()));
            }
        }
        self::afficheVue('view.php',
            [
                "pagetitle" => "Résultats",
                "cheminVueBody" => "proposition/resultatPropositions.php",
                "title" => "Résultats",
                "subtitle" => "Résultats de la question : " . $question->getTitre(),
                "propositions" => $propositions,
                "sections" => $sections,
                "textes" => $textes,
                "responsables" => $responsables,
                "voteUrl" => $voteUrl,
                "resultats" => $resultats,
                "idQuestion" => $_GET['idQuestion'],
            ]);
    }

    public static function createProposition(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $roles = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
        if (!ConnexionUtilisateur::estConnecte()
            || !ConnexionUtilisateur::creerProposition($question->getIdQuestion())
            || in_array('Responsable', $roles)) {
            (new Notification())->ajouter("danger","Vous ne pouvez pas créer une proposition !");
            self::redirection("?controller=question&all");
        }
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
        if ($question) {
            self::afficheVue('view.php',
                [
                    "pagetitle" => "Creation",
                    "sections" => $sections,
                    "responsable" => ConnexionUtilisateur::getUtilisateurConnecte(),
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
        $roles = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
        if (!ConnexionUtilisateur::estConnecte()
            || !ConnexionUtilisateur::creerProposition($question->getIdQuestion())
            || in_array('Responsable', $roles)) {
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
        $isOk &= (new PropositionRepository())->AjouterResponsable($_POST['organisateur'], $idProposition, NULL, $_POST['idQuestion'], 0);
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
        $roles = ConnexionUtilisateur::getRolesProposition($idProposition);
        if (!$proposition->isVisible() || !(count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
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
        $idProposition = $_POST['idProposition'];
        $proposition = (new PropositionRepository())->select($idProposition);
        $roles = ConnexionUtilisateur::getRolesProposition($idProposition);
        if (!$proposition->isVisible() || !(count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
            (new Notification())->ajouter("danger","Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        // $question = (new QuestionRepository())->select($idQuestion);
        $isOk = true;
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $textsection = nl2br(htmlspecialchars($_POST['section' . $i]));
            $texte = new Texte($_POST['idQuestion'], $_POST['idSection' . $i], $idProposition, $textsection, NULL);
            $isOk = (new TexteRepository())->modifier($texte);
        }
        if ($_POST['coAuteur'] != "")
            $isOk &= (new PropositionRepository())->ajouterCoauteur($_POST['coAuteur'], $idProposition);

        if ($isOk) (new Notification())->ajouter("success", "La proposition a été modifiée.");
        else (new Notification())->ajouter("danger", "La proposition n'a pas pu être modifiée.");
        self::redirection("?controller=proposition&action=readProposition&idQuestion=" . $_POST['idQuestion'] . "&idProposition=" . $idProposition);
    }

    public static function readProposition(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger","Vous devez vous connecter !");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $textes = (new TexteRepository())->selectAllByKey($_GET['idProposition']);
        $filsRaw = (new PropositionRepository())->getFilsFusion($_GET['idProposition']);
        $fils = [];
        foreach ($filsRaw as $filsR) {
            $fils[] = (new PropositionRepository())->select($filsR[0]);
        }
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
                    "visibilite" => $proposition->isVisible(),
                    "question" => $question,
                    "fils" => $fils,
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
        $roles = ConnexionUtilisateur::getRolesProposition($idProposition);
        if (!$proposition->isVisible() || !(count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
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
        $roles = ConnexionUtilisateur::getRolesProposition($idProposition);
        if (!$proposition->isVisible() || !(count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
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


    public static function createFusion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $roles = ConnexionUtilisateur::getRolesProposition($proposition->getIdProposition());
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
        if (!in_array('Responsable', $roles)
            && !(in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::questionValide($question->getIdQuestion()))) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        if (!$proposition->isVisible() || !$question->getPeriodeActuelle() == 'Période d\'écriture') {
            (new Notification())->ajouter("danger", "La proposition ne peut pas être fusionnée !");
            self::redirection("?controller=question&action=all");
        }
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

        $coAuteurs = array_unique(array_merge($coAuteursCourant, $coAuteursAMerge), SORT_REGULAR);
        if (!in_array($respCourant, $coAuteurs)) $coAuteurs[] = $respCourant;
        if (in_array($respAMerge, $coAuteurs)) unset($coAuteurs[array_search($respAMerge, $coAuteurs)]);
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
        $respCourant = $_POST['respCourant'];
        $respAMerge = $_POST['respAMerge'];
        $idOldProp = $_POST['idPropCourant'];
        $idOldPropMerge = $_POST['idPropAMerge'];
        $roles = ConnexionUtilisateur::getRolesProposition($idOldProp);
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($_POST['idQuestion']);
        $proposition = (new PropositionRepository())->select($idOldProp);
        $question = (new QuestionRepository())->select($_POST['idQuestion']);
        if (!in_array('Responsable', $roles)
            && !(in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::questionValide($_POST['idQuestion']))) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits !");
            self::redirection("?controller=question&action=all");
        }
        if (!$proposition->isVisible() || !$question->getPeriodeActuelle() == 'Période d\'écriture') {
            (new Notification())->ajouter("danger", "La proposition ne peut pas être fusionnée !");
            self::redirection("?controller=question&action=all");
        }
        $isOk = true;
        $isOk &= (new PropositionRepository())->modifierProposition($idOldProp, 'invisible', null);
        $isOk &= (new PropositionRepository())->modifierProposition($idOldPropMerge, 'invisible', null);
        $idNewProp = (new PropositionRepository())->ajouterProposition('visible');
        $isOk &= (new PropositionRepository())->modifierProposition($idOldProp, 'invisible', $idNewProp);
        $isOk &= (new PropositionRepository())->modifierProposition($idOldPropMerge, 'invisible', $idNewProp);
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $texte = new Texte($_POST['idQuestion'], $_POST['idSection' . $i], $idNewProp, $_POST['section' . $i], null);
            $isOk = (new TexteRepository())->sauvegarder($texte);
        }
        foreach ($_POST['coAuteurs'] as $coAuteur) {
            $isOk &= (new PropositionRepository())->ajouterCoauteur($coAuteur, $idNewProp);
        }
        $isOk &= (new PropositionRepository())->AjouterResponsable($respAMerge, $idNewProp, $idOldPropMerge, $_POST['idQuestion'], 1);
        (new PropositionRepository())->ajouterCoAuteur($respAMerge, $idOldProp);
        (new PropositionRepository())->ajouterCoAuteur($respCourant, $idOldPropMerge);

        if ($isOk) (new Notification())->ajouter("success", "La fusion a été réalisée avec succès.");
        else {
            (new PropositionRepository())->supprimer($idNewProp);
            (new PropositionRepository())->modifierProposition($idOldProp, 'visible', null);
            (new PropositionRepository())->modifierProposition($idOldPropMerge, 'visible', null);
            (new Notification())->ajouter("danger", "La fusion n'a pas pu être réalisée.");
        }
        self::redirection("?controller=question&action=readQuestion&idQuestion=" . $_POST['idQuestion']);
    }

}