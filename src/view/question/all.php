<?php
// PERMET DE VOIR TOUTES LES QUESTIONS DU SITE
use App\Votee\Lib\ConnexionUtilisateur;

if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::creerQuestion()) {
    echo '<a href="./frontController.php?controller=question&action=section">            
            <div class="flex gap-2">
                <p>Créer une question</p>
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
foreach ($questions as $question) {
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