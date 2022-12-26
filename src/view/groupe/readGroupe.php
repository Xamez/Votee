<?php

use App\Votee\Controller\AbstractController;

$rawIdGroupe = rawurlencode($groupe->getIdGroupe());

echo '<h1 class="title text-dark text-2xl font-semibold mt-10">Membres</h1>
      <div class="flex flex-wrap gap-2 justify-center gap-2 border-2 p-8 rounded-3xl">';
if (sizeof($membres) > 0) {
    foreach ($membres as $membre) {
        echo '<div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                <span class="material-symbols-outlined">account_circle</span>'
                        . htmlspecialchars($membre->getPrenom()) . ' ' . htmlspecialchars($membre->getNom()) . '
              </div>';
    }
} else {
    echo '<span>Aucun membre</span>';
}
echo '</div>
      <div class="flex gap-2 justify-between">';
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'readAllGroupe', 'title' => 'Retour', "logo" => 'reply']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'updateGroupe', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Editer', "logo" => 'edit']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'deleteGroupe', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Supprimer', "logo" => 'delete']);
echo '</div>';