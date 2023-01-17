<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\MotDePasse;
use App\Votee\Lib\Notification;
use App\Votee\Model\DataObject\Periodes;
use App\Votee\Model\DataObject\Proposition;
use App\Votee\Model\DataObject\Texte;
use App\Votee\Model\DataObject\Vote;
use App\Votee\Model\Repository\CommentaireRepository;
use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Model\Repository\PropositionRepository;
use App\Votee\Model\Repository\QuestionRepository;
use App\Votee\Model\Repository\SectionRepository;
use App\Votee\Model\Repository\TexteRepository;
use App\Votee\Model\Repository\UtilisateurRepository;
use App\Votee\Model\Repository\VoteRepository;
use App\Votee\parsedown\Parsedown;

class ControllerProposition extends AbstractController {

    public static function createVote($idQuestion, $idVotant, $idProposition, $isRedirected): void {
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

    public static function createdVote(): void {
        $roles = ConnexionUtilisateur::getRolesQuestion($_POST['idQuestion']);
        $question = (new QuestionRepository())->select($_POST['idQuestion']);
        if (!(count(array_intersect(['Responsable', 'Organisateur', 'Votant'], $roles)) > 0)) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&readAllQuestion");
        } else if ($question->getPeriodeActuelle() == Periodes::VOTE->value) {
            $vote = (new VoteRepository())->voter($question, $_POST['idVotant'], $_POST['idProposition'], $_POST['noteProposition']);
            if ($vote)
                (new Notification())->ajouter("success", "Le vote a été effectué.");
            else
                (new Notification())->ajouter("warning", "Le vote existe déjà.");
            if (array_key_exists("isRedirected", $_POST) && $_POST['isRedirected'])
                self::redirection("?controller=proposition&action=readProposition&idQuestion=" . $_POST['idQuestion'] . "&idProposition=" . $_POST['idProposition']);
            else
                self::redirection("?controller=proposition&action=voterPropositions&idQuestion=" . $_POST['idQuestion']);
        } else {
            (new Notification())->ajouter("danger", "Vous ne pouvez pas voter en dehors de la période de vote.");
            self::redirection("?controller=proposition&action=readProposition&idQuestion=" . $_POST['idQuestion'] . '&idProposition=' . $_POST['idProposition']);
        }
    }

    public static function voterPropositions(): void {
        if (ConnexionUtilisateur::getUtilisateurConnecte() == null) {
            (new Notification())->ajouter("danger", "Vous devez être connecté pour accéder à cette page.");
            self::redirection("?controller=question&action=readQuestion&idQuestion=" . $_GET['idQuestion']);
        } else {
            $idVotant = ConnexionUtilisateur::getUtilisateurConnecte()->getLogin();
            $question = (new QuestionRepository())->select($_GET['idQuestion']);
            if ($question->getPeriodeActuelle() != Periodes::VOTE->value) {
                (new Notification())->ajouter("danger", "Impossible de voter sur cette question.");
                self::redirection("?controller=question&action=all");
            } else {
                $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
                $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion" => $_GET['idQuestion']));
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
        }
    }

    public static function resultatPropositions(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if ($question->getPeriodeActuelle() != Periodes::RESULTAT->value) {
            (new Notification())->ajouter("danger", "Impossible d'accéder aux résultats de cette question.");
            self::redirection("?controller=question&readAllQuestion");
        } else {
            $voteType = $question->getVoteType();
            $voteType = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($voteType))));
            $voteType = strtolower(substr($voteType, 0, 1)) . substr($voteType, 1);
            $voteUrl = 'proposition/vote/resultat/' . $voteType . '.php';

            $question = (new QuestionRepository())->select($_GET['idQuestion']);
            $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
            $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion" => $_GET['idQuestion']));
            $resultats = (new VoteRepository())->getResultats($question);
            foreach ($propositions as $proposition) {
                $idProposition = $proposition->getIdProposition();
                $responsables[$idProposition] = (new UtilisateurRepository())->selectResp($idProposition);
                $textess = (new TexteRepository())->selectAllByKey($idProposition);
                $textes[$idProposition] = $textess;
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
    }

    public static function createProposition(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $rolesQuestion = ConnexionUtilisateur::getRolesQuestion($_GET['idQuestion']);
        if (!ConnexionUtilisateur::estConnecte()
            || ConnexionUtilisateur::hasPropositionVisible($question->getIdQuestion())
            || !(in_array("Organisateur", $rolesQuestion) || ConnexionUtilisateur::creerProposition($question->getIdQuestion()))) {
            (new Notification())->ajouter("danger", "Vous ne pouvez pas créer une proposition.");
            self::redirection("?controller=question&action=all");
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
                ]);
        } else {
            self::error("La question n'existe pas");
        }
    }

    public static function createdProposition(): void {
        $idQuestion = $_POST['idQuestion'];
        $question = (new QuestionRepository())->select($idQuestion);
        $rolesQuestion = ConnexionUtilisateur::getRolesQuestion($idQuestion);
        if (!ConnexionUtilisateur::estConnecte()
            || ConnexionUtilisateur::hasPropositionVisible($question->getIdQuestion())
            || !(in_array("Organisateur", $rolesQuestion) || ConnexionUtilisateur::creerProposition($question->getIdQuestion()))) {
            (new Notification())->ajouter("danger", "Vous ne pouvez pas créer une proposition.");
            self::redirection("?controller=question&action=all");
        }
        $proposition = new Proposition(NULL, NULL,  $_POST['titreProposition'], 'visible', NULL );
        $idProposition = (new PropositionRepository())->sauvegarderSequence($proposition);
        $isOk = true;
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $textsection = nl2br(htmlspecialchars($_POST['section' . $i]));
            $texte = new Texte(
                $idQuestion,
                $_POST['idSection' . $i],
                $idProposition,
                $textsection,
                NULL
            );
            $isOk = (new TexteRepository())->sauvegarder($texte);
        }

        $isOk &= (new PropositionRepository())->ajouterResponsable($_POST['organisateur'], $idProposition, NULL, $idQuestion, 0);
        if ($isOk) {
            (new Notification())->ajouter("success", "La proposition a été créée.");
            self::redirection("?controller=proposition&action=addCoauteur&idQuestion=$idQuestion&idProposition=$idProposition");
        } else {
            (new PropositionRepository())->supprimer($idProposition);
            (new Notification())->ajouter("warning", "L'ajout de la proposition a échoué.");
            self::redirection("?controller=proposition&action=createProposition&idQuestion=$idQuestion");
        }
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
                "title" => "Suppression d'un vote",
            ]);
    }

    public static function updateProposition(): void {
        $idProposition = $_GET['idProposition'];
        $idQuestion = $_GET['idQuestion'];
        $proposition = (new PropositionRepository())->select($idProposition);
        if (ConnexionUtilisateur::estAdministrateur() || !self::hasPermission($idQuestion, $idProposition, ['Responsable', 'CoAuteur'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);
        $textes = (new TexteRepository())->selectAllByKey($idProposition);
        $textesMarkdown = [];
        foreach ($textes as $texte) {
            $parsedown = new Parsedown();
            $textesMarkdown[] = new Texte(
                $textes[0]->getIdQuestion(),
                $textes[0]->getIdSection(),
                $textes[0]->getIdProposition(),
                $parsedown->text($texte->getTexte()),
                $textes[0]->getLike(),
            );
        }
        if ($question && $textes) {
            $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);
            $responsable = (new UtilisateurRepository())->selectResp($idProposition);
            $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($idProposition);
            $specialiste = (new UtilisateurRepository())->select($question->getLoginSpecialiste());
            $commentaires = (new CommentaireRepository())->getCommentaireByIdProposition($idProposition);
            self::afficheVue('view.php',
                [
                    "question" => $question,
                    "proposition" => $proposition,
                    "idProposition" => $_GET['idProposition'],
                    "sections" => $sections,
                    "coAuteurs" => $coAuteurs,
                    "textesMarkdown" => $textesMarkdown,
                    "textes" => $textes,
                    "responsable" => $responsable,
                    "specialiste" => $specialiste,
                    "commentaires" => $commentaires,
                    "pagetitle" => "Edition de proposition",
                    "cheminVueBody" => "proposition/updateProposition.php",
                    "title" => "Édition de la proposition",
                ]);
        } else {
            self::error("La proposition ou la question n'existe pas.");
        }
    }

    public static function updatedProposition(): void {
        $idProposition = $_POST['idProposition'];
        $idQuestion = $_POST['idQuestion'];
        if (ConnexionUtilisateur::estAdministrateur() || !self::hasPermission($idQuestion, $idProposition, ['Responsable', 'CoAuteur'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $proposition = (new PropositionRepository())->select($idProposition);
        $proposition->setTitreProposition($_POST['titreProposition']);
        $isOk = true;
        $isOk &= (new PropositionRepository())->modifier($proposition);
        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $idSection = $_POST['idSection' . $i];
            $oldText = (new TexteRepository())->selectAllByMultiKey(array("idQuestion" => $idQuestion, "idSection" => $idSection, "idProposition" => $idProposition))[0];
            $oldTextSection = $oldText->getTexte();
            $textSection = htmlspecialchars($_POST['section' . $i]);
            $text = new Texte($idQuestion, $idSection, $idProposition, $textSection, NULL);
            $isOk = (new TexteRepository())->modifier($text);
            if ($oldTextSection != $textSection)
                $isOk &= (new CommentaireRepository)->supprimerCommentaireSiSectionModifier($_POST['idProposition'], $i);
        }

        if ($isOk) {
            (new Notification())->ajouter("success", "La proposition a été modifiée.");
            self::redirection("?controller=proposition&action=readProposition&idProposition=$idProposition&idQuestion=$idQuestion");
        } else {
            (new Notification())->ajouter("danger", "La modification a échoué.");
            self::redirection("?controller=proposition&action=updateProposition&idQuestion=$idQuestion&idProposition=$idProposition");
        }
    }

    public static function addCoauteur(): void {
        $idProposition = $_GET['idProposition'];
        $idQuestion = $_GET['idQuestion'];
        if (!self::hasPermission($idQuestion, $idProposition,['Responsable'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $question = (new QuestionRepository())->select($idQuestion);

        $exception = (new UtilisateurRepository())->selectAllAdmins();
        $responsables = (new UtilisateurRepository())->selectRespQuestion($idQuestion);
        $exception = array_merge($responsables, $exception);

        $utilisateurs = (new UtilisateurRepository())->selectAll();
        $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($idProposition);
        if ($coAuteurs) $exception = array_merge($exception, $coAuteurs);
        $utilisateur = array_udiff($utilisateurs, $exception, function ($a, $b) {
            return strcmp($a->getLogin(), $b->getLogin());
        });
        self::afficheVue('view.php',
            [
                "pagetitle" => "Ajouter des co-auteurs",
                "idProposition" => $idProposition,
                "idQuestion" => $idQuestion,
                "utilisateurs" => $utilisateur,
                "coAuteurs" => $coAuteurs,
                "cheminVueBody" => "proposition/addCoauteur.php",
                "title" => "Ajouter des co-auteurs",
                "subtitle" => "Ajouter un ou plusieurs co-auteurs à la proposition"
            ]);
    }

    public static function addedCoauteur(): void {
        $idProposition = $_POST['idProposition'];
        $idQuestion = $_POST['idQuestion'];
        if (!self::hasPermission($idQuestion, $idProposition, ['Responsable'])) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        $oldCoAuteur = (new UtilisateurRepository())->selectCoAuteur($idProposition);
        $coAuteurs = [];
        foreach ($oldCoAuteur as $coAuteur) $coAuteurs[] = $coAuteur->getLogin();
        if (array_key_exists('coAuteurs', $_POST)) $coAuteurs = array_diff($coAuteurs, $_POST['coAuteurs']);
        $isOk = true;
        if (isset($_POST['utilisateurs'])) {
            foreach ($_POST['utilisateurs'] as $login) {
                $isOk = (new PropositionRepository())->ajouterCoAuteur($login, $idProposition);
            }
        }
        foreach ($coAuteurs as $login) {
            $isOk = (new PropositionRepository())->supprimerCoAuteur( $login, $idProposition);
        }

        if ($isOk) (new Notification())->ajouter("success", "Les co-auteurs ont été mis à jour avec succès.");
        else (new Notification())->ajouter("warning", "Certains co-auteurs n'ont pas pu être mis à jour.");
        self::redirection("?controller=proposition&action=readProposition&idProposition=$idProposition&idQuestion=$idQuestion");
    }

    public static function readProposition(): void {
        $idQuestion = $_GET['idQuestion'];
        $idProposition = $_GET['idProposition'];
        self::redirectConnexion("?controller=utilisateur&action=connexion");
        $rolesQuestion = ConnexionUtilisateur::getRolesQuestion($idQuestion);
        $question = (new QuestionRepository())->select($idQuestion);
        $propsGagnante = (new VoteRepository())->getPropositionsGagantes($question);
        if (!($question->getPeriodeActuelle() == Periodes::ECRITURE->value && (count(array_intersect($rolesQuestion, ['Responsable', 'CoAuteur', 'Specialiste'])) > 0))
            && !(in_array($question->getPeriodeActuelle(), [Periodes::RESULTAT->value, Periodes::VOTE->value, Periodes::TRANSITION->value]) && (count(array_intersect($rolesQuestion, ['Responsable', 'CoAuteur', 'Votant'])) > 0))
            && !in_array('Organisateur', $rolesQuestion)
            && !($question->getPeriodeActuelle() == Periodes::RESULTAT->value && in_array($idProposition, $propsGagnante))) {
            (new Notification())->ajouter("danger", "Vous ne pouvez pas accéder à cette proposition.");
            self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
        }

        $proposition = (new PropositionRepository())->select($idProposition);
        $textes = (new TexteRepository())->selectAllByKey($idProposition);
        $filsRaw = (new PropositionRepository())->getFilsFusion($idProposition);
        $fils = [];
        foreach ($filsRaw as $filsR) {
            $fils[] = (new PropositionRepository())->select($filsR[0]);
        }
        foreach ($textes as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }
        $demandesCours = (new DemandeRepository())->selectAllByMultiKey(['login' => ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(),
            'TITREDEMANDE' => 'fusion', 'ETATDEMANDE' => 'attente', 'IDPROPOSITION' => $idProposition, 'IDQUESTION' => $idQuestion]);
        $isDemande = sizeof($demandesCours) > 0;
        if ($question && $textes) {
            $sections = (new SectionRepository())->selectAllByKey($idQuestion);
            $responsable = (new UtilisateurRepository())->selectResp($idProposition);
            $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($idProposition);
            $commentaires = (new CommentaireRepository())->getCommentaireByIdProposition($idProposition);
            self::afficheVue('view.php',
                [
                    "visibilite" => $proposition->isVisible(),
                    "question" => $question,
                    "fils" => $fils,
                    "commentaires" => $commentaires,
                    "idProposition" => $_GET['idProposition'],
                    "sections" => $sections,
                    "coAuteurs" => $coAuteurs,
                    "textes" => $textes,
                    "titreProposition" => $proposition->getTitreProposition(),
                    "responsable" => $responsable,
                    "isDemande" => $isDemande,
                    "pagetitle" => "Question",
                    "cheminVueBody" => "proposition/readProposition.php",
                    "title" => $question->getTitre(),
                ]);
        } else {
            self::error("La proposition ou la question n'existe pas");
        }
    }

    public static function deleteProposition(): void {
        $idProposition = $_GET['idProposition'];
        $idQuestion = $_GET['idQuestion'];
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($idQuestion);
        if (!self::hasPermission($idQuestion, $idProposition, ['Responsable']) && !in_array('Organisateur', $rolesQuest)) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=utilisateur&action=connexion");
        }
        self::afficheVue('view.php',
            [
                "idQuestion" => $idQuestion,
                "idProposition" => $idProposition,
                "pagetitle" => "Archiver la proposition",
                "cheminVueBody" => "proposition/deleteProposition.php",
                "title" => "Archivage",
            ]);
    }

    public static function deletedProposition(): void {
        $idProposition = $_POST['idProposition'];
        $idQuestion = $_POST['idQuestion'];
        $motDePasse = $_POST['motDePasse'];
        $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($idQuestion);
        if (!self::hasPermission($idQuestion, $idProposition, ['Responsable']) && !in_array('Organisateur', $rolesQuest)) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        if (!MotDePasse::verifier($motDePasse, $utilisateur->getMotDePasse())) {
            (new Notification())->ajouter("warning", "Mot de passe incorrect.");
            self::redirection("?controller=proposition&action=deleteProposition&idQuestion=$idQuestion&idProposition=$idProposition");
        }
        $proposition = (new PropositionRepository())->select($idProposition);
        $proposition->setVisibilite('invisible');
        if ((new PropositionRepository())->modifier($proposition)) {
            (new Notification())->ajouter("success", "La proposition a été supprimée.");
        } else (new Notification())->ajouter("warning", "La suppression à echoué.");
        self::redirection("?controller=question&action=readQuestion&idQuestion=$idQuestion");
    }

    public static function createdCommentaire(): void {
        $commentaire = (array) json_decode($_POST['commentaire']);
        if ((new CommentaireRepository())->ajouterCommentaireEtStocker($commentaire['idQuestion'], $commentaire['idProposition'],
            $commentaire['numeroParagraphe'], $commentaire['indexCharDebut'],
            $commentaire['indexCharFin'], $commentaire['texteCommentaire'])) {
            (new Notification())->ajouter("success", "Le commentaire a été ajouté.");
        } else {
            (new Notification())->ajouter("warning", "Le commentaire n'a pas pu être ajouté.");
        }
    }

    public static function updatedCommentaire(): void {
        $commentaireData = (array) json_decode($_POST['commentaire']);
        $commentaire = (new CommentaireRepository())->getCommentaireById($commentaireData['idCommentaire']);
        if ($commentaire != null) {
            if ($commentaire->getTexteCommentaire() != $commentaireData['texteCommentaire']) {
                $commentaire->setTexteCommentaire($commentaireData['texteCommentaire']);
                (new CommentaireRepository())->modifier($commentaire);
                (new Notification())->ajouter("success", "Le commentaire a été modifié.");
            }
        } else {
            (new Notification())->ajouter("warning", "Le commentaire n'a pas pu être modifié.");
        }
    }

    public static function deletedCommentaire(): void {
        $commentaire = (array) json_decode($_POST['commentaire']);
        if((new CommentaireRepository())->supprimer($commentaire['idCommentaire'])) {
            (new Notification())->ajouter("success", "Le commentaire a été supprimé.");
        } else {
            (new Notification())->ajouter("warning", "Le commentaire n'a pas pu être supprimé.");
        }
    }

    public static function createFusion(): void {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $proposition = (new PropositionRepository())->select($_GET['idProposition']); // Proposition de la personne dont on veut créer une fusion
        $roles = ConnexionUtilisateur::getRolesProposition($proposition->getIdProposition()); // Recuperation du role de la personne qui est censé posseder la proposition dont on veut créer une fusion
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
        if (!in_array('Responsable', $roles)
            && !(in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::hasPropositionVisible($question->getIdQuestion()))) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        if (!$proposition->isVisible() || !$question->getPeriodeActuelle() == Periodes::ECRITURE->value) {
            (new Notification())->ajouter("danger", "La proposition ne peut pas être fusionnée.");
            self::redirection("?controller=question&action=all");
        }
        $sections = (new SectionRepository())->selectAllByKey($_GET['idQuestion']);

        $textesCourant = (new TexteRepository())->selectAllByKey($_GET['idProposition']); // get
        foreach ($textesCourant as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }

        // Proposition de la personne connectée
        $idPropAMerge = ConnexionUtilisateur::getPropByLoginVisible($_GET['idQuestion']); // Get l'id de la proposition de la personne connectée
        $texteAMerge = (new TexteRepository())->selectAllByKey($idPropAMerge);
        foreach ($texteAMerge as $texte) {
            $parsedown = new Parsedown();
            $texte->setTexte($parsedown->text($texte->getTexte()));
        }

        $respCourant = (new UtilisateurRepository())->selectResp($_GET['idProposition']); // Responsable de la proposition dont on veut créer une fusion
        $respAMerge = (new UtilisateurRepository())->selectResp($idPropAMerge); // Responsable de la proposition de la personne connectée

        $coAuteursCourant = (new UtilisateurRepository())->selectCoAuteur($_GET['idProposition']); // CoAuteurs de la proposition dont on veut créer une fusion
        $coAuteursAMerge = (new UtilisateurRepository())->selectCoAuteur($idPropAMerge); // CoAuteurs de la proposition de la personne connectée

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
        $respCourant = $_POST['respCourant']; // Responsable de la proposition dont on veut créer une fusion
        $respAMerge = $_POST['respAMerge']; // Responsable de la proposition de la personne connectée
        $idOldProp = $_POST['idPropCourant']; // Proposition dont on veut créer une fusion
        $idOldPropMerge = $_POST['idPropAMerge']; // Proposition de la personne connectée
        $roles = ConnexionUtilisateur::getRolesProposition($idOldProp);
        $rolesQuest = ConnexionUtilisateur::getRolesQuestion($_POST['idQuestion']);
        $proposition = (new PropositionRepository())->select($idOldProp);
        $oldProposition = (new PropositionRepository())->select($idOldPropMerge);
        $question = (new QuestionRepository())->select($_POST['idQuestion']);
        if (!in_array('Responsable', $roles)
            && !(in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::hasPropositionVisible($_POST['idQuestion']))) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection("?controller=question&action=all");
        }
        if (!$proposition->isVisible() || !$question->getPeriodeActuelle() == Periodes::ECRITURE->value) {
            (new Notification())->ajouter("danger", "La proposition ne peut pas être fusionnée.");
            self::redirection("?controller=question&action=all");
        }
        $isOk = true;

        /* Met en invisible la proposition pour permettre la creation d'une nouvelle proposition */
        $oldProp = (new PropositionRepository())->select($idOldProp);
        $oldProp->setVisibilite('invisible');
        $isOk &= (new PropositionRepository())->modifier($oldProp);

        $newProposition = new Proposition(NULL, NULL,  $_POST['titreProposition'], 'visible', NULL);
        $idNewProp = (new PropositionRepository())->sauvegarderSequence($newProposition);

        /* Modification des propositions pour stocker l'id de la proposition parent */
        $oldPropMerge = (new PropositionRepository())->select($idOldPropMerge);
        $oldPropMerge->setVisibilite('invisible');
        $oldPropMerge->setIdPropFusionParent($idNewProp);
        $isOk &= (new PropositionRepository())->modifier($oldPropMerge);

        $oldProp = (new PropositionRepository())->select($idOldProp);
        $oldProp->setVisibilite('invisible');
        $oldProp->setIdPropFusionParent($idNewProp);
        $isOk &= (new PropositionRepository())->modifier($oldProp);

        for ($i = 0; $i < $_POST['nbSections'] && $isOk; $i++) {
            $texte = new Texte($_POST['idQuestion'], $_POST['idSection' . $i], $idNewProp, $_POST['section' . $i], null);
            $isOk = (new TexteRepository())->sauvegarder($texte);
        }
        foreach ($_POST['coAuteurs'] as $coAuteur) {
            $isOk &= (new PropositionRepository())->ajouterCoauteur($coAuteur, $idNewProp);
        }
        $isOk &= (new PropositionRepository())->ajouterResponsable($respAMerge, $idNewProp, $idOldPropMerge, $_POST['idQuestion'], 1);
        $isOk &= (new PropositionRepository())->ajouterCoAuteur($respAMerge, $idOldProp);
        $isOk &= (new PropositionRepository())->ajouterCoAuteur($respCourant, $idOldPropMerge);
        if ($isOk) (new Notification())->ajouter("success", "La fusion a été réalisée avec succès.");
        else {
            (new PropositionRepository())->supprimer($idNewProp);
            (new PropositionRepository())->modifier($proposition); // Remet la proposition en visible (récupère la version d'origine)
            (new PropositionRepository())->modifier($oldProposition);
            (new PropositionRepository())->supprimerCoAuteur($respAMerge, $idOldProp);
            (new PropositionRepository())->supprimerCoAuteur($respCourant, $idOldPropMerge);
            (new Notification())->ajouter("danger", "La fusion n'a pas pu être réalisée.");
        }
        self::redirection("?controller=question&action=readQuestion&idQuestion=" . $_POST['idQuestion']);
    }

    public static function readCoauteur(): void {
        $idProposition = $_GET['idProposition'];
        $idQuestion = $_GET['idQuestion'];
        $coAuteurs = (new UtilisateurRepository())->selectCoAuteur($idProposition);
        self::afficheVue('view.php',
            [
                "pagetitle" => "Liste des co-auteurs",
                "coAuteurs" => $coAuteurs,
                "idProposition" => $idProposition,
                "idQuestion" => $idQuestion,
                "cheminVueBody" => "proposition/readCoauteur.php",
                "title" => "Co-auteurs",
            ]);
    }

    /** Retourne true si la proposition est visible, si la question est en phase d'ecriture et si l'utilisateur a les roles requis */
    public static function hasPermission($idQuestion, $idProposition, $rolesArray): bool {
        $question = (new QuestionRepository())->select($idQuestion);
        $roles = ConnexionUtilisateur::getRolesProposition($idProposition);
        $proposition = (new PropositionRepository())->select($idProposition);
        return $question->getPeriodeActuelle() == Periodes::ECRITURE->value && $proposition->isVisible() && (count(array_intersect($rolesArray, $roles)) > 0);
    }

}