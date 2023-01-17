<?php

$values = ['-3', '-2', '-1', '1', '2', '3'];
$colors = ['ef4444', 'f97316', 'facc15', '22D3EE', '34D399', '22c55e']; // [red-500, orange-500, yellow-400, cyan-400, emerald-400, green-500]
$labels = ['A rejeter', 'Insuffisant', 'Passable', 'Assez bien', 'Bien', 'Très Bien'];

echo '<div class="flex flex-col gap-4 result-element">';

foreach ($resultats as $idProposition => $resultat) {

    echo '<div data-id="' . $idProposition . '" class="proposition hidden absolute w-3/5 z-10 left-1/2" style="transform: translateX(-50%)">';
    echo '<span class="absolute material-symbols-outlined text-red-500 top-4 right-6 cursor-pointer">close</span>';
    echo '<div class="flex flex-col gap-5 p-8 rounded-3xl bg-white shadow-4xl overflow-y-auto" style="max-height: 60vh">';
    foreach ($sections as $index=>$section) {
        $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
        $sectionTexteHTML = $textes[$idProposition][$index]->getTexte();
        echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>';
        echo '<div class="text-dark proposition-markdown break-all text-justify">' . $sectionTexteHTML . '</div>';
    }
    echo '</div>';
    echo '</div>';

    echo '<div class="flex flex-col lg:flex-row items-center justify-center gap-4">';

    $responsable = $responsables[$idProposition];
    $resultat = $resultat[1];

    echo '
    <div data-id="' . $idProposition . '" class="user flex items-center lg:w-1/4 cursor-pointer">
        <div class="flex gap-1 ' . ($resultat == $resultatGagnant ? "bg-green-400 text-white" : "bg-white text-main"). ' shadow-md rounded-2xl w-fit p-2 items-center">
            <span class="material-symbols-outlined pr-1">' . ($resultat == $resultatGagnant ? "military_tech" : "account_circle") . '</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
        </div>
    </div>
    ';

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

}

echo '<div class="flex flex-col justify-center items-center gap-2">';
    echo '<p>Légende</p>';
    echo '<div class="flex flex-wrap justify-center gap-2">';
    for ($i = 0; $i < sizeof($values); $i++) {
        echo '<div class="flex h-8 text-center items-center rounded-md" style="background-color: #' . $colors[$i] .'"><p class="text-white p-2">' . $labels[$i] . '</p></div>';
    }
    echo '</div>';
echo '</div>';

echo '</div>';