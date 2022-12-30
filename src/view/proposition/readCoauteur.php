<div class="flex flex-wrap gap-2 justify-center">
    <?php

    use App\Votee\Controller\AbstractController;

    foreach ($coAuteurs as $key=> $coAuteur) {
        echo '<a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($coAuteur->getLogin()). '">
                <div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <div class="flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $coAuteur->getPrenom() . ' ' . $coAuteur->getNom() . '</div>
                </div>
              </a>';
    }
    ?>
</div>
<div class="flex gap-2 justify-between">
    <?php AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'readProposition', 'params' => 'idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition), 'title' => 'Retour', "logo" => 'reply']); ?>
</div>