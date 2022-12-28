<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Controller\ControllerProposition;
use App\Votee\Lib\ConnexionUtilisateur;
require "propositionHeader.php";

$roles = ConnexionUtilisateur::getRolesProposition($idProposition);
$rolesQuest = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
$rawIdProposition = rawurlencode($idProposition);
$rawIdQuestion = rawurlencode($question->getIdQuestion());

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
foreach ($sections as $index=>$section) {
    echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . htmlspecialchars($section->getTitreSection()) . '</h1>
          <div class="proposition-markdown break-all text-justify">' . $textes[$index]->getTexte() . '</div>';
}
echo '</div>
      <div class="flex gap-2 justify-between">';
            AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'readQuestion', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Retour', "logo" => 'reply']);

if ($visibilite && $question->getPeriodeActuelle() == 'Période d\'écriture') {
    if ((count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
        AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'updateProposition', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Editer', "logo" => 'edit']);
        if (in_array('Responsable', $roles)) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'addCoauteur', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'CoAuteurs', "logo" => 'manage_accounts']);
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'deleteProposition', 'params' => 'idQuestion=' . $rawIdQuestion . '&idProposition=' . $rawIdProposition, 'title' => 'Supprimer', "logo" => 'delete']);
        }
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