<?php

use App\Votee\Controller\ControllerProposition;
use App\Votee\Lib\ConnexionUtilisateur;

require "propositionHeader.php";
echo '</p><div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
foreach ($sections as $index=>$section) {
    $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
    $sectionDescHTML = $textes[$index]->getTexte();

    echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
              <div class="proposition-markdown break-all text-justify">' . $sectionDescHTML . '</div>';
}
echo '</div><div class="flex gap-2 justify-between">
        <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . $question->getIdQuestion() . '">
            <div class="flex gap-2">
                <span class="material-symbols-outlined">reply</span>
                <p>Retour</p>
            </div>
        </a>';
if ($question->getPeriodeActuelle() == 'Période d\'écriture') {
    if (ConnexionUtilisateur::getRoleProposition($idProposition) == 'representant'
        || ConnexionUtilisateur::getRoleProposition($idProposition) == 'coauteur') {
        echo '
            <a href="./frontController.php?controller=proposition&action=updateProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    <p>Editer</p>
                </div
            </a>';
        if (ConnexionUtilisateur::getRoleProposition($idProposition) == 'representant') {
            echo ' <a href="./frontController.php?controller=proposition&action=selectFusion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">upload</span>
                    <p>Fusionner</p>
                </div
            </a>';
        }
        echo '<a href="./frontController.php?controller=proposition&action=deleteProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">delete</span>
                    <p>Supprimer</p>
                </div>
            </a>
            </div>';
    }
}
echo '</div>';

if ($question->getPeriodeActuelle() == 'Période de vote') {
    ControllerProposition::createVote(rawurlencode($question->getIdQuestion()), ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(), $idProposition, true);
}