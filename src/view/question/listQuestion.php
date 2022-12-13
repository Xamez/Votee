<?php

use App\Votee\Lib\ConnexionUtilisateur;
if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::creerQuestion()) {
    echo '<a href="./frontController.php?controller=question&action=section">            
            <div class="flex gap-2">
                <p>Créer un vote</p>
                <span class="material-symbols-outlined">add_circle</span>
            </div>
          </a>';
} else if (ConnexionUtilisateur::estConnecte() && !ConnexionUtilisateur::creerQuestion()) {
    echo '<a href="./frontController.php?controller=demande&action=createDemande&titreDemande=question">
            <div class="flex gap-2">
                <p>Faire une demande</p>
                <span class="material-symbols-outlined">file_copy</span>
            </div>
          </a>';
}
echo '<div class="flex flex-col gap-10 mt-10">
        <h1 class="title text-dark text-2xl font-semibold">Organisateur</h1>
        <div class="flex flex-col gap-3">';
foreach ($questionsOrga as $question) {
    echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">'
                . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">'
                        . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
         </a>';
}
if (!$questionsOrga) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Representant</h1>
      <div class="flex flex-col gap-3">';
foreach ($questionsRepre as $question) {
    echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">'
                . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">'
                        . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
         </a>';
}
if (!$questionsRepre) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">CoAuteur</h1>
      <div class="flex flex-col gap-3">';
foreach ($questionsCoau as $question) {
    echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">'
                    . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">'
                        . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
         </a>';
}
if (!$questionsVota) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Votant</h1>
      <div class="flex flex-col gap-3">';
foreach ($questionsVota as $question) {
    echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">'
                    . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">'
                        . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
         </a>';
}
if (!$questionsVota) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
    </div>';
