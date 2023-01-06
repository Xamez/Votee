<?php

use App\Votee\Controller\ControllerProposition;
use App\Votee\Lib\ConnexionUtilisateur;

echo '<script type="text/javascript" src="assets/js/accordion.js"></script>';

echo '<div class="flex flex-col gap-y-4">';
foreach ($propositions as $proposition) {

    if ($proposition->getVisibilite() == 'invisible') continue;

    $idProposition = $proposition->getIdProposition();

    echo '<div>
              <div class="accordion text-left w-full p-2 cursor-pointer flex justify-between p-2 items-center rounded">
                  <div class="flex items-center gap-2">
                      <div class="bg-white items-center flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$idProposition]->getNom()) . ' ' . htmlspecialchars($responsables[$idProposition]->getPrenom()) . '
                      </div>
                      <span>' . htmlspecialchars($proposition->getTitreProposition()) . '</span>
                  </div>
                  <div class="flex items-center gap-2">';
                      if ($aVote[$idProposition]) {
                          echo '<div class="bg-main items-center flex gap-1 text-white shadow-md rounded-2xl w-fit p-2" >
                                    <p>Déjà voté</p>
                                </div >';
                      }
                      echo '<span class="accordion-arrow material-symbols-outlined">arrow_forward_ios</span>
                  </div>
              </div>                        
              <div class="flex p-4 overflow-hidden hidden panel">';
                  echo '<div class="pb-6">';
                  foreach ($sections as $index => $section) {
                      $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
                      $sectionDescHTML = $textes[$idProposition][$index]->getTexte();
                      echo '
                          <h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
                          <div class="proposition-markdown break-all text-justify">' . $sectionDescHTML . '</div>
                          ';

                  }
                  echo '</div>';
                  ControllerProposition::createVote(rawurlencode($idQuestion), ConnexionUtilisateur::getUtilisateurConnecte()->getLogin(), $idProposition, false);
              echo '</div>';
        echo '</div>';
}

echo '</div>';

echo '
<div class="flex gap-2 justify-between">
    <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($idQuestion) . '">
        <div class="flex gap-2">
            <span class="material-symbols-outlined">reply</span>
            <p>Retour</p>
        </div>
    </a>
<div>
';