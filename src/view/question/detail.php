<?php
echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Représentant : 
        <div class="flex gap-1 text-main shadow-md rounded-2xl w-fit p-2"><span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($representant->getNom()) . ' ' . htmlspecialchars($representant->getPrenom()) .
    '</div></p></div>';

echo '<h1>Organisation</h1>';
foreach ($sections as $section) {
    echo "<p>" . $section->getTitreSection() . "</p>";
}
echo '<h1>Calendrier</h1><p>Période décriture : du '. $question->getDateDebutQuestion().' au ' . $question->getDateFinQuestion() .'.</p>';

echo '<h1>Proposition</h1>';

foreach ($sections as $section) {
    echo '<div><p>Proposition de : </p></div>';
}