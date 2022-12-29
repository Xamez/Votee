<?php
echo '<div class="flex flex-col gap-5">
         <div class="flex items-center gap-2">
             <div class="flex gap-2 items-center">
                <span class="text-main font-semibold">Responsable : </span>
                <a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($responsable->getLogin()). '">
                    <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>'
                        . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
                    </div>
                </a>
             </div>
         </div>
         <div class="flex items-center flex-wrap gap-2 pt-0"><p class="text-main font-semibold">Co-auteur :';
if ($coAuteurs)
foreach ($coAuteurs as $coAuteur) {
    echo '<a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($coAuteur->getLogin()). '">
            <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl p-2">
                <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($coAuteur->getPrenom()) . ' ' . htmlspecialchars($coAuteur->getNom()) . '
            </div>
          </a>';
} else {
    echo '<p class="text-main">Aucun</p>';
}
echo '</div>';
if (isset($visibilite) && !$visibilite) {
    echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Etat : </p><span class="text-main">Archivée</span></div>';
}
echo '</div>';