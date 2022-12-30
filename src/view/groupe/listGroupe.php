<?php

use App\Votee\Controller\AbstractController;

AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'createGroupe', 'title' => 'Créer un groupe', "logo" => 'add_circle']);

echo '<div class="flex flex-col gap-3">';
foreach ($groupes as $groupe) {
    echo '<a href="./frontController.php?controller=groupe&action=readGroupe&idGroupe=' . rawurlencode($groupe->getIdGroupe()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">'
                . htmlspecialchars($groupe->getNomGroupe()) . '
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
         </a>';
}
echo '</div>';