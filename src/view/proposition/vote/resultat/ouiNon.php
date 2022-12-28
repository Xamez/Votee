<?php

$values = ['-1', '1', '0'];
$textes = ['de Non', 'de Oui', 'd\'Abstention'];
$colors = ['f87171', '22c55e', 'facc15'];

echo '<div class="flex flex-col gap-4 result-element">';

$i = 0;

foreach ($resultats as $idProposition => $resultat) {

    echo '<div class="flex flex-col gap-1 lg:flex-row justify-center">';

    $responsable = $responsables[$idProposition];

    echo '
    <div class="flex" style="width: 25%">
        <div class="flex gap-1 ' . ($i == 0 ? "bg-green-400 text-white" : "bg-white text-main"). ' shadow-md rounded-2xl w-fit p-2 items-center">
            <span class="material-symbols-outlined pr-1">' . ($i == 0 ? "military_tech" : "account_circle") . '</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
        </div>
    </div>
    ';

    $resultat = $resultat[1];

    echo '<div class="flex w-full gap-4 items-center cursor-pointer">';

    for ($i = 0; $i < sizeof($values); $i++) {
        foreach ($resultat as $note => $nombre) {
            if ($values[$i] == $note) {
                echo '
                    <div class="flex h-1/2 p-4 items-center justify-center rounded-md" style="width: 33%; background-color: #' . $colors[$i] . '">
                        <p class="text-center text-white">' . $nombre . '% ' . $textes[$i] . '</p>
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