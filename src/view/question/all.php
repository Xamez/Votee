<?php
use App\Votee\Lib\ConnexionUtilisateur;

echo '<form action="./frontController.php?controller=question&action=all" method="GET">
        <input id="search" name="search" type="text" placeholder="Titre de question" value="'. ($_GET['search'] ?? "") . '">
        <input type="hidden" name="action" value="all">
        <input type="hidden" name="controller" value="question">
        <input id="submit" type="submit" value="Search">
      </form>';
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