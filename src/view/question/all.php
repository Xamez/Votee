<form action="./frontController.php?controller=question&action=all" method="GET">
    <div class="flex px-3 w-80 rounded-3xl border-solid border-zinc-800 border-2">
        <input class="w-12 bg-transparent rounded-none material-symbols-outlined" id="submit" type="submit" value="search">
        <input class="search-field w-full" id="search" name="search" maxlength="100" type="text" placeholder="Titre de question" value="<?= ($_GET['search'] ?? "") ?>">
    </div>
    <input type="hidden" name="action" value="all">
    <input type="hidden" name="controller" value="question">
</form>
<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Lib\ConnexionUtilisateur;

if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::creerQuestion()) {
    AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'section', 'title' => 'Créer une question', "logo" => 'add_circle']);
} else if (ConnexionUtilisateur::estConnecte() && !ConnexionUtilisateur::creerQuestion()) {
    AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=question', 'title' => 'Faire une demande', "logo" => 'file_copy']);
}
echo '<div class="flex flex-col gap-3">';
foreach ($questions as $question) {
    echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex flex-col justify-between items-center bg-light p-2 rounded md:flex-row">'
                . htmlspecialchars($question->getTitre()) . '
                <div class="flex flex-col items-center gap-2 md:flex-row">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">'
                        . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
         </a>';
}
echo '</div>';