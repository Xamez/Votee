<?php
use App\Votee\Lib\ConnexionUtilisateur;
require "propositionHeader.php";

echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
foreach ($sections as $index=>$section) {
    $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
    $sectionDescHTML = $textes[$index]->getTexte();

    echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
              <div class="proposition-markdown break-all text-justify">' . $sectionDescHTML . '</div>';
}
echo '</div>
      <div class="flex gap-2 justify-between">
        <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . $question->getIdQuestion() . '">
            <div class="flex gap-2">
                <span class="material-symbols-outlined">reply</span>
                <p>Retour</p>
            </div
        </a>';
if ($visibilite == 'visible' && $question->getPeriodeActuelle() == 'Période d\'écriture') {
    if (ConnexionUtilisateur::getRoleProposition($idProposition) == 'representant'
        || ConnexionUtilisateur::getRoleProposition($idProposition) == 'coauteur') {
        echo '<a href="./frontController.php?controller=proposition&action=updateProposition&idQuestion='
                    . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    <p>Editer</p>
                </div
            </a>';
        echo '<a href="./frontController.php?controller=proposition&action=deleteProposition&idQuestion='
                    . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">delete</span>
                    <p>Supprimer</p>
                </div>
            </a>';
    }
    //TODO Empecher la fusion si on a pas une proposition dans la meme question et si notre proposition est invisible
    if (ConnexionUtilisateur::getRoleProposition($idProposition) != 'representant') {
        if (ConnexionUtilisateur::creerFusion($idProposition)) {
            echo '<a href="./frontController.php?controller=proposition&action=createFusion&idQuestion='
                . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">upload</span>
                    <p>Créer une fusion</p>
                </div
              </a>';
        } else {
            echo ' <a href="./frontController.php?controller=demande&action=createDemande&titreDemande=fusion&idQuestion='
                . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">file_copy</span>
                    <p>Demander une fusion</p>
                </div
              </a>';
        }
    }
    echo '</div>';
}