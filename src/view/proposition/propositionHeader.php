<?php
echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Responsable : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) .
        '</div>
        </p>
     </div>';

echo '<div class="flex items-center flex-wrap gap-2 pt-0"><p class="text-main font-semibold">Co-auteur :';
if ($coAuteurs)
foreach ($coAuteurs as $coAuteur) {
    echo '<div class="flex gap-1 text-main bg-white shadow-md rounded-2xl p-2">
            <span class="material-symbols-outlined">account_circle</span>'
        . htmlspecialchars($coAuteur->getPrenom()) . ' ' . htmlspecialchars($coAuteur->getNom()) .
        '</div>';
} else {
    echo '<p class="text-main">Aucun</p>';
}
echo '</div>';