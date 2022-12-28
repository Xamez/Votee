<?php

$values = ['-2', '-1', '0', '1', '2'];
$colors = ['f87171', 'fb923c', 'facc15', '4ade80', '22d3ee'];

echo '<div class="flex flex-col gap-4 result-element">';


$i = 0;

foreach ($resultats as $idProposition => $resultat) {

    echo '<div class="flex flex-col lg:flex-row justify-center">';

    $responsable = $responsables[$idProposition];

    echo '
    <div class="flex" style="width: 25%">
        <div class="flex gap-1 ' . ($i == 0 ? "bg-green-400 text-white" : "bg-white text-main"). ' shadow-md rounded-2xl w-fit p-2 items-center">
            <span class="material-symbols-outlined pr-1">' . ($i == 0 ? "military_tech" : "account_circle") . '</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
        </div>
    </div>
    ';

    $resultat = $resultat[1];

    echo '<div class="flex w-full items-center cursor-pointer result-element-bar">';

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

echo '</div>';