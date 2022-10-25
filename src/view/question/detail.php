<?php
// affiche le login de l'utilisateur qui a créé la question
echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Représentant : 
        <div class="flex gap-1 text-main shadow-md rounded-2xl w-fit p-2"><span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($question->getLogin()) .
     '</div></p></div>';


// TODO: afficher co-auteurs (EXEMPLE ICI)
echo '<div class="flex items-center flex-wrap gap-2 pt-0"><p class="text-main font-semibold">Co-auteurs :</p>';
for($i = 0; $i < 8; $i++)
    echo '<div class="flex gap-1 text-main shadow-md rounded-2xl p-2"><span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars("A faire") . '</div>';
echo '</div>';

echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
$index=0;
foreach ($sections as $section) {
    $index++;
    $sectionTitreHTML = htmlspecialchars($section->getTitre());
    $sectionDescHTML = htmlspecialchars($section->getTexte());

    echo
    '<h1 class="text-main text-2xl font-bold">'
        . $index . ' - ' . $sectionTitreHTML .
    '</h1>
    <p class="text-justify">'. $sectionDescHTML .'</p>';
}
echo '</div>';