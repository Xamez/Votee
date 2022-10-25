<?php
// affiche le login de l'utilisateur qui a créé la question
echo '<div class="flex items-center gap-2"><p class="text-main">Représentant : 
        <div class="flex gap-1 text-main shadow-md rounded-2xl w-fit p-2"><span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($question->getLogin())
    . '</div></p></div><div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
$index=0;
foreach ($sections as $section) {
    $index++;
    $sectionTitreHTML = htmlspecialchars($section->getTitre());
    $sectionDescHTML = htmlspecialchars($section->getTexte());

    echo '<h1 class="text-main text-2xl font-bold">' . $index . ' - ' . $sectionTitreHTML . '</h1><p>'. $sectionDescHTML .'</p>';
}
echo '</div>';