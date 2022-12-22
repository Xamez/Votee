<?php
use App\Votee\Lib\ConnexionUtilisateur;

echo '<form action="./frontController.php?controller=question&action=all" method="GET">
        <div class="flex px-3 w-80 rounded-3xl border-solid border-zinc-800 border-2">
            <input class="w-12 bg-transparent rounded-none material-symbols-outlined" id="submit" type="submit" value="search">
            <input class="search-field w-full" id="search" name="search" maxlength="100" type="text" placeholder="Titre de question" value="'. ($_GET['search'] ?? "") . '">
        </div>       
        <input type="hidden" name="action" value="all">
        <input type="hidden" name="controller" value="question">
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