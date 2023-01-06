<div class="flex md:justify-between md:flex-row flex-col gap-4 items-center">
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
    echo '<div class="flex">';
    if (!ConnexionUtilisateur::estAdministrateur() && ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::creerQuestion()) {
        AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'section', 'title' => 'Créer une question', "logo" => 'add_circle']);
    } else if (!ConnexionUtilisateur::estAdministrateur() && ConnexionUtilisateur::estConnecte() && !ConnexionUtilisateur::creerQuestion()) {
        AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=question', 'title' => 'Faire une demande', "logo" => 'file_copy']);
    }
echo '</div>
    </div>
    <div class="flex flex-col gap-3">';
foreach ($questions as $question) {
    echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-3 md:p-2 rounded gap-3">
                <div class="flex flex-col-reverse md:flex-row justify-between w-full gap-3">
                    <div class="flex flex-col md:flex-row gap-3 md:items-center items-left"> 
                        <span>' . htmlspecialchars($question->getTitre()) . '</span>
                    </div>
                    <div class="flex flex-col items-start gap-2">
                        <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">
                            <span>' . $question->getPeriodeActuelle() . '</span>
                        </div>
                    </div>
                </div>
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
         </a>';
}
echo '</div>';