<div class="flex flex-col gap-8 mt-10">
    <div class="rounded-xl py-4 flex flex-col gap-10 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Groupes</h1>
        <div class="flex flex-wrap gap-2 justify-center">
        <?php

        use App\Votee\Controller\AbstractController;

        foreach ($groupes as $key=> $groupe) {
            echo '<a href="./frontController.php?controller=groupe&action=readGroupe&idGroupe=' . rawurlencode($groupe->getIdGroupe()). '">
                    <div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                        <div class="flex gap-1 items-center" for="groupe' . $key . '"><span class="material-symbols-outlined">group</span>' . $groupe->getNomGroupe() . '</div>
                    </div>
                  </a>';
        }
        if (sizeof($groupes) == 0) echo '<span>Aucun groupe</span>';
        ?>
        </div>
    </div>
    <div class="rounded-xl py-4 flex flex-col gap-10 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Votants</h1>
        <div class="flex flex-wrap gap-2 justify-center">
        <?php
        foreach ($votants as $key=> $votant) {
                echo '<a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($votant->getLogin()). '">
                        <div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                            <div class="flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $votant->getPrenom() . ' ' . $votant->getNom() . '</div>
                        </div>
                      </a>';
        }
        if (sizeof($votants) == 0) echo '<span>Aucun votant</span>';
        ?>
        </div>
    </div>
    <div class="flex gap-2 justify-between">
        <?php AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'readQuestion', 'params' => 'idQuestion=' . rawurlencode($question->getIdQuestion()), 'title' => 'Retour', "logo" => 'reply']); ?>
    </div>
</div>