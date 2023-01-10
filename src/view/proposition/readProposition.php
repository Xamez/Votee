<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Controller\ControllerProposition;
use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Model\DataObject\Periodes;

require "propositionHeader.php";

$roles = ConnexionUtilisateur::getRolesProposition($idProposition);
$rolesQuest = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
$rawIdProposition = rawurlencode($idProposition);
$rawIdQuestion = rawurlencode($question->getIdQuestion());

echo '
<div id="ids">
    <input type="hidden" value="' . rawurlencode($question->getIdQuestion()) . '">
    <input type="hidden" value="' . rawurlencode($idProposition) . '">
</div>';

if ($fils) {
    echo '<div class="flex gap-7 flex-col md:items-start items-center md:flex-row">
             <p class="text-main font-semibold">Fusionnée avec : </p>';
    foreach ($fils as $key=>$f) {
        echo '<a class="flex items-center gap-2 text-main" href="./frontController.php?controller=proposition&action=readProposition&idProposition='
                . rawurlencode($f->getIdProposition()) . '&idQuestion='. rawurlencode($question->getIdQuestion()).'">
                <span class="material-symbols-outlined">description</span>
                <span>Proposition ' . $key+1 . '</span>
              </a>';
    }
    echo '</div>';
}

$commentaryEnabled = count(array_intersect(['Organisateur', 'Specialiste'], $rolesQuest)) > 0 || count(array_intersect(['Responsable'], $roles));
AbstractController::afficheVue('detailProposition.php', ['commentaryEnabled' => $commentaryEnabled, 'inAccordion' => false, 'titreProposition' => $titreProposition,'sections' => $sections, 'textes' => $textes, 'commentaires' => $commentaires]);

if ($visibilite && count($rolesQuest) > 0 && $question->getPeriodeActuelle() == Periodes::VOTE->value) {
    ControllerProposition::createVote(rawurlencode($question->getIdQuestion()), ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(), $idProposition, true);
}

echo '<div class="flex flex-col sm:flex-row justify-center gap-2 justify-between">';

AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'readQuestion', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Retour', "logo" => 'reply']);

if ($visibilite && $question->getPeriodeActuelle() == Periodes::ECRITURE->value) {

    echo '<script type="text/javascript" src="assets/js/commentary.js"></script>';

    if (count(array_intersect(['Specialiste'], $rolesQuest)) > 0) {

        echo '
            <div id="popup" class="hidden shadow-4xl fixed z-1 bg-white text-main rounded-xl p-4">
                <div class="flex flex-col items-center justify-center gap-2">
                    <p class="text-md font-bold border-0 select-none">Ecrivez un commentaire</p>
                    <textarea id="text-commentary" class="border-2 text-black max-h-60 h-44 w-96 rounded-xl ring-0 focus:outline-none" maxlength="300" placeholder="Entrez votre commentaire..." type="text" required></textarea>
                    <button id="create-commentary" class="text-lg font-bold p-2 text-white bg-main font-semibold rounded-lg">Ajouter un commentaire</button>
                </div>
            </div>
            ';


        echo '
        <a class="button justify-center flex bg-white border-lightPurple text-main hover:text-white border-2 p-2 rounded-3xl cursor-pointer">
            <div id="commentary-button" class="flex gap-2">
                <span class="material-symbols-outlined">sticky_note_2</span>
                <p class="line-through">Commentaire</p>
            </div>
        </a>
        ';
    }

    if ((count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'updateProposition', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Editer', "logo" => 'edit']);
        }
        if (in_array('Responsable', $roles)) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'addCoauteur', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'CoAuteurs', "logo" => 'manage_accounts']);
        }
    }
    if (!in_array('Responsable', $roles)
        && (in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::hasPropositionVisible($question->getIdQuestion()))) {
        if (!$isDemande) {
            if (ConnexionUtilisateur::creerFusion($idProposition)) {
                AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'createFusion', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Créer une fusion', "logo" => 'upload']);
            } else {
                AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=fusion&idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Demander une fusion', "logo" => 'file_copy']);
            }
        } else {
            AbstractController::afficheVue('button.php', ['controller' => 'utilisateur', 'action' => 'historiqueDemande', 'title' => 'Voir ma demande', "logo" => 'info']);
        }
    }
    if (in_array('Responsable', $roles) || in_array('Organisateur',$rolesQuest)) {
        AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'deleteProposition', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Supprimer', "logo" => 'delete']);
    }
}

echo '</div>';