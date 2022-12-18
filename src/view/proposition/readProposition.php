<?php

use App\Votee\Controller\ControllerProposition;
use App\Votee\Lib\ConnexionUtilisateur;
require "propositionHeader.php";

$roles = ConnexionUtilisateur::getRolesProposition($idProposition);
$rolesQuest = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());

if ($fils) {
    echo '<div class="flex gap-5">
        <p class="text-main font-semibold">Fusionné avec : </p>';
    foreach ($fils as $key=>$f) echo '<a class="text-main" href="./frontController.php?controller=proposition&action=readProposition&idProposition='
            . rawurlencode($f->getIdProposition()) . '&idQuestion='. rawurlencode($question->getIdQuestion()).'">Proposition ' . $key+1 . '</a>';
    echo '</div>';
}

echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
foreach ($sections as $index=>$section) {
    echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . htmlspecialchars($section->getTitreSection()) . '</h1>
              <div class="proposition-markdown break-all text-justify">' . $textes[$index]->getTexte() . '</div>';
}
echo '
        </div>
        <div class="flex gap-2 justify-between">
            <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">reply</span>
                    <p>Retour</p>
                </div>
            </a>';

if ($visibilite && $question->getPeriodeActuelle() == 'Période d\'écriture') {
    if ((count(array_intersect(['Responsable', 'CoAuteur'], $roles)) > 0)) {
        echo '<a href="./frontController.php?controller=proposition&action=updateProposition&idQuestion='
                    . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    <p>Editer</p>
                </div>
            </a>';
        echo '<a href="./frontController.php?controller=proposition&action=deleteProposition&idQuestion='
                    . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">delete</span>
                    <p>Supprimer</p>
                </div>
            </a>';
    }
    if (!in_array('Responsable', $roles)
        && (in_array('Responsable', $rolesQuest) && ConnexionUtilisateur::questionValide($question->getIdQuestion()))) {
        if (ConnexionUtilisateur::creerFusion($idProposition)) {
            echo '<a href="./frontController.php?controller=proposition&action=createFusion&idQuestion='
                . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">upload</span>
                    <p>Créer une fusion</p>
                </div>
              </a>';
        } else {
            echo ' <a href="./frontController.php?controller=demande&action=createDemande&titreDemande=fusion&idQuestion='
                . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">file_copy</span>
                    <p>Demander une fusion</p>
                </div>
              </a>';
        }
    }
}

if ($visibilite && $question->getPeriodeActuelle() == 'Période de vote') {
    ControllerProposition::createVote(rawurlencode($question->getIdQuestion()), ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(), $idProposition, true);
}
echo '</div>';