<div class="headerProp md:grid items-center flex flex-col gap-5">
    <span class="text-main text-center md:text-left w-28 font-semibold">Responsable : </span>
    <div class="flex">
        <a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=<?= rawurlencode($responsable->getLogin())?>">
            <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                <span class="material-symbols-outlined">account_circle</span>
                <?= htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) ?>
            </div>
        </a>
    </div>
    <span class="text-main text-center w-28 font-semibold w-28 md:text-left">Co-auteur :</span>
    <div>
        <div class="flex md:justify-start justify-center items-center flex-wrap gap-2 pt-0">
<?php
if (sizeof($coAuteurs) == 0) echo '<p class="text-main">Aucun</p>';
else {
    $compteur = 0;
    foreach ($coAuteurs as $coAuteur) {
        if ($compteur < 10) {
            echo '<a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($coAuteur->getLogin()) . '">
                <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($coAuteur->getPrenom()) . ' ' . htmlspecialchars($coAuteur->getNom()) . '
                </div>
              </a>';
            $compteur++;
        }
    }
}
if (sizeof($coAuteurs) > 10) echo '
                <a class="flex items-center gap-2 p-2 text-white bg-main font-semibold rounded-2xl" 
                   href="./frontController.php?controller=proposition&action=readCoauteur&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition='. $_GET['idProposition'] . '">
                    <span class="material-symbols-outlined">more_horiz</span>Voir plus
                </a>';
echo '    </div>
      </div>';
if (isset($visibilite) && !$visibilite) {
    echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Etat : </p><span class="text-main">Archivée</span></div>';
}
echo '</div>';
