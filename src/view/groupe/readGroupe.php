<div class="rounded-xl py-4 flex flex-col gap-8 mt-7 bg-lightPurple">
    <h1 class="title text-dark text-2xl font-semibold">Membres</h1>
    <div class="flex flex-wrap justify-center gap-2 p-2">
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
echo '</div>
</div>';
if (ConnexionUtilisateur::estAdministrateur()) {
    echo '<div class="flex flex-col md:flex-row gap-2 justify-between">';
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'readAllGroupe', 'title' => 'Retour', "logo" => 'reply']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'updateGroupe', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Editer', "logo" => 'edit']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'addMembre', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Membres', "logo" => 'manage_accounts']);
    AbstractController::afficheVue('button.php', ['controller' => 'groupe', 'action' => 'deleteGroupe', 'params' => 'idGroupe=' . $rawIdGroupe, 'title' => 'Supprimer', "logo" => 'delete']);
    echo '</div>';
}