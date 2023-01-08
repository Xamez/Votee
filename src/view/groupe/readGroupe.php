<h1 class="title text-dark text-2xl font-semibold mt-10">Membres</h1>
<div class="flex flex-wrap gap-2 justify-center gap-2 border-2 p-8 rounded-3xl">
<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Lib\ConnexionUtilisateur;

$rawIdGroupe = rawurlencode($groupe->getIdGroupe());
if (sizeof($membres) > 0) {
    foreach ($membres as $membre) {
        echo '<a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($membre->getLogin()). '">
                <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span>'
                        . htmlspecialchars($membre->getPrenom()) . ' ' . htmlspecialchars($membre->getNom()) . '
                </div>
              </a>';
    }
} else {
    echo '<span>Aucun membre</span>';
}
echo '</div>';
if (ConnexionUtilisateur::estAdministrateur()) {
    echo '<div class="flex flex-col md:flex-row gap-2 justify-between">';
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'readAllGroupe', 'title' => 'Retour', "logo" => 'reply']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'updateGroupe', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Editer', "logo" => 'edit']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'addMembre', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Membres', "logo" => 'manage_accounts']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'deleteGroupe', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Supprimer', "logo" => 'delete']);
    echo '</div>';
}