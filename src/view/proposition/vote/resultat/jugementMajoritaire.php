<?php

$values = ['-2', '-1', '0', '1', '2'];
$colors = ['f87171', 'fb923c', 'facc15', '4ade80', '22d3ee'];

# TODO: investiguer pourquoi j'ai besoin de get deux fois le [$idProposition]

foreach ($resultats as $idProposition => $resultat) {

    echo '<div class="flex flex-col lg:flex-row justify-center">';

    $responsable = $responsables[$idProposition];

    echo '
    <div class="flex pr-3" style="width: 25%">
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2 items-center">
            <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
        </div>
    </div>
    ';

    echo '<div class="flex w-full items-center cursor-pointer">';

    echo '<div class="relative bg-black h-8" style="width: 2px; left: 50%;"></div>'; // affiche la médiane

    for ($i = 0; $i < sizeof($values); $i++) {
        foreach ($resultat[$idProposition] as $note => $nombre) {
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