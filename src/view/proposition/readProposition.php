<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Controller\ControllerProposition;
use App\Votee\Lib\ConnexionUtilisateur;
require "propositionHeader.php";

$roles = ConnexionUtilisateur::getRolesProposition($idProposition);
$rolesQuest = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
$rawIdProposition = rawurlencode($idProposition);
$rawIdQuestion = rawurlencode($question->getIdQuestion());


echo '
<div id="ids">
    <input type="hidden" value="' . rawurlencode($question->getIdQuestion()) . '">
    <input type="hidden" value="' . rawurlencode($idProposition) . '">
</div>
';

if ($fils) {
    echo '<div class="flex gap-5">
             <p class="text-main font-semibold">Fusionné avec : </p>';
    foreach ($fils as $key=>$f) {
        echo '<a class="text-main" href="./frontController.php?controller=proposition&action=readProposition&idProposition='
                . rawurlencode($f->getIdProposition()) . '&idQuestion='. rawurlencode($question->getIdQuestion()).'">Proposition ' . $key+1 . '</a>';
    }
    echo '</div>';
}

echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';

//foreach ($sections as $index=>$section) {
//    echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . htmlspecialchars($section->getTitreSection()) . '</h1>
//          <div class="proposition-markdown break-all text-justify">' . $textes[$index]->getTexte() . '</div>';
//}

foreach ($sections as $numParagraphe => $section) {
    $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
    $sectionDescHTML = $textes[$numParagraphe]->getTexte();

    $paragraph = "";
    $paragraphRaw = "";

    $sectionDescHTMLChars = str_split($sectionDescHTML);

    foreach ($sectionDescHTMLChars as $key => $char) {

        foreach ($commentaires as $commentaire) {
            if ($commentaire->getNumeroParagraphe() == $numParagraphe) {
                if ($commentaire->getIndexCharDebut() === $key)
                    $paragraph .= '<span id="' . $commentaire->getIdCommentaire() . '" class="commentary cursor-pointer bg-light" data-id="' . htmlspecialchars($commentaire->getTexteCommentaire()) . '">';
                else if ($commentaire->getIndexCharFin() == $key)
                    $paragraph .= '</span>';
            }
        }
        $paragraphRaw .= $char;
        $paragraph .= $char;
    }

    echo '
        <h1 class="text-main text-2xl font-bold">'. $numParagraphe + 1 . ' - ' . $sectionTitreHTML . '</h1>
        <div data-id="' . $paragraphRaw . '" id="' . $numParagraphe .'" class="proposition-markdown break-all text-justify">
            ' . $paragraph . '
        </div>
    ';
}

echo '</div>
      <div class="flex gap-2 justify-between">';
            AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'readQuestion', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Retour', "logo" => 'reply']);

if ($visibilite && $question->getPeriodeActuelle() == 'Période d\'écriture') {

    //if ($visibilite && (count(array_intersect(['Specialiste'], $rolesQuest)) > 0)) { TODO REMETTRE QD SPECIALISTE IMPLEMENTE
    if ($visibilite ) {

        echo '<script type="text/javascript" src="assets/js/commentary.js"></script>';

        echo '
            <div id="popup" class="hidden fixed z-1 bg-main text-white rounded-xl p-4">
                <div class="flex flex-col items-center justify-center gap-2">
                    <p class="text-md font-bold border-0 select-none">Ecrivez un commentaire</p>
                    <textarea id="text-commentary" class="border-2 max-h-60 h-44 w-96 bg-main ring-0 focus:outline-none" maxlength="300" placeholder="Entrez votre commentaire..." type="text" required></textarea>
                    <button id="create-commentary" class="text-xl font-bold hover:underline underline-1">Ajouter un commentaire</button>
                </div>
            </div>
            ';


        echo '
        <a class="cursor-pointer">
            <div id="commentary-button" class="flex gap-2">
                <span class="material-symbols-outlined">sticky_note_2</span>
                <p class="line-through">Commentaire</p>
            </div>
        </a>
        ';
    }

    if ((count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
        AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'updateProposition', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Editer', "logo" => 'edit']);
        AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'deleteProposition', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Supprimer', "logo" => 'delete']);

    }
    if (!in_array('Responsable', $roles)
        && (in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::questionValide($question->getIdQuestion()))) {
        if (ConnexionUtilisateur::creerFusion($idProposition)) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'createFusion', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Créer une fusion', "logo" => 'upload']);
        } else {
            AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=fusion&idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Demander une fusion', "logo" => 'file_copy']);
        }
    }
}


if ($visibilite && $question->getPeriodeActuelle() == 'Période de vote') {
    ControllerProposition::createVote(rawurlencode($question->getIdQuestion()), ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(), $idProposition, true);
}
echo '</div>';