<h1 class="title text-dark text-2xl font-semibold">Groupes</h1>


<h1 class="title text-dark text-2xl font-semibold">Votants</h1>
<div class="flex flex-wrap gap-2 justify-center">
<?php

use App\Votee\Controller\AbstractController;

foreach ($votants as $key=> $votant) {
        echo '<div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                <div class="flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $votant->getPrenom() . ' ' . $votant->getNom() . '</div>
              </div>';
    }
?>
</div>
<div class="flex gap-2 justify-between">
    <?php AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'readQuestion', 'params' => 'idQuestion=' . rawurlencode($question->getIdQuestion()), 'title' => 'Retour', "logo" => 'reply']); ?>
</div>