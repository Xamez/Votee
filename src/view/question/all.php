<div class="flex md:justify-between md:flex-row flex-col gap-4 items-center">
    <form action="./frontController.php?controller=question&action=all" method="GET" class="flex flex-col lg:flex-row gap-2">
        <div class="flex px-3 rounded-3xl border-solid border-lightPurple border-2">
            <input class="w-12 bg-transparent rounded-none material-symbols-outlined text-main cursor-pointer" id="submit" type="submit" value="search">
            <input class="filter-field w-full bg-white text-main" id="search" name="search" maxlength="100" type="text" placeholder="Titre de question" value="<?= ($_GET['search'] ?? "") ?>">

            <input class=" w-12 pl-2 border-l border-lightPurple bg-transparent rounded-none material-symbols-outlined text-main cursor-pointer" id="submit" type="submit" value="filter_alt">
            <select name="periode" class="filter-field p-2 rounded-md bg-white text-main"">
                <option value="">Aucun filtre</option>
                <?php
                use App\Votee\Model\DataObject\Periodes;
                $periodes = Periodes::toArray();
                foreach ($periodes as $periode => $periodeName) {
                    echo '<option value="' . $periodeName . '" ' . ($periodeName == ($_GET['periode'] ?? "") ? "selected" : "") . '>' . $periodeName . '</option>';
                }
                ?>
            </select>
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
        if (!$isDemande) {
            AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=question', 'title' => 'Faire une demande', "logo" => 'file_copy']);
        } else {
            AbstractController::afficheVue('button.php', ['controller' => 'utilisateur', 'action' => 'historiqueDemande', 'title' => 'Voir ma demande', "logo" => 'info']);
        }
    }
echo '</div>
    </div>
    <div class="flex flex-col gap-3">';
$nbQuestionInvisible = 0;
if (sizeof($questions) != 0) {
    foreach ($questions as $question) {
        if ($question->getPeriodeActuelle() != Periodes::PREPARATION->value) {
        echo '<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-3 md:p-2 rounded gap-3">
                <div class="flex flex-col-reverse md:flex-row justify-between w-full gap-3">
                    <div class="flex flex-col md:flex-row gap-3 md:items-center items-left"> 
                        <span class="text-ellipsis overflow-hidden whitespace-nowrap">' . htmlspecialchars($question->getTitre()) . '</span>
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
        } else $nbQuestionInvisible++;
    }
}
if (sizeof($questions) == $nbQuestionInvisible) echo '<h1 class="flex justify-center text-main text-2xl font-bold pt-8">Aucune question trouvée</h1>';

echo '</div>';