<?php

$values = ['-2', '-1', '0', '1', '2'];
$colors = ['f87171', 'fb923c', 'facc15', '4ade80', '22d3ee'];
$labels = ["Insuffisant", "Passable", "Abstention", "Bien", "Très Bien"];

echo '<div class="flex flex-col gap-4 result-element">';

$i = 0;

foreach ($resultats as $idProposition => $resultat) {

    echo '<div data-id="' . $idProposition . '" class="proposition hidden absolute h-4/5 w-3/5 z-10 left-1/2" style="transform: translateX(-50%)">';
    echo '<span class="absolute material-symbols-outlined text-red-500 top-4 right-6 cursor-pointer">close</span>';
    echo '<div class="flex flex-col gap-5 p-8 rounded-3xl bg-home">';
    foreach ($sections as $index=>$section) {
        $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
        $sectionTexteHTML = $textes[$idProposition][$index]->getTexte();
        echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>';
        echo '<div class="text-dark proposition-markdown break-all text-justify">' . $sectionTexteHTML . '</div>';
    }
    echo '</div>';
    echo '</div>';

    echo '<div class="flex flex-col lg:flex-row gap-2 items-center justify-center">';

    $responsable = $responsables[$idProposition];

    echo '
    <div data-id="' . $idProposition . '" class="user flex items-center lg:w-1/4 cursor-pointer">
        <div class="flex gap-1 ' . ($i == 0 ? "bg-green-400 text-white" : "bg-white text-main"). ' shadow-md rounded-2xl w-fit p-2 items-center">
            <span class="flex material-symbols-outlined pr-1">' . ($i == 0 ? "military_tech" : "account_circle") . '</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
        </div>
    </div>
    ';

    $resultat = $resultat[1];

    echo '<div class="flex w-full items-center result-element-bar">';

    echo '<div class="relative bg-black h-8 " style="width: 2px; left: 50%;"></div>'; // affiche la médiane

    for ($i = 0; $i < sizeof($values); $i++) {
        foreach ($resultat as $note => $nombre) {
            if ($values[$i] == $note) {
                echo '
                    <div class="flex h-8 items-center justify-center" style="width: ' . $nombre . '%; background-color: #' . $colors[$i] . '">
                        <p class="text-center text-white">' . $nombre . '%</p>
                    </div>
                    ';
            }
        }
    }
    echo '</div>';
    echo '</div>';

    $i++;
}

echo '<div class="flex flex-col gap-2 justify-center items-center flex-col md:flex-row pt-6">';
echo '<p>Légende</p>';
for ($i = 0; $i < sizeof($values); $i++) {
    echo '<div class="flex h-8 text-center items-center rounded-md" style="background-color: #' . $colors[$i] .'"><p class="text-white p-2">' . $labels[$i] . '</p></div>';
}
echo '</div>';

echo '</div>';