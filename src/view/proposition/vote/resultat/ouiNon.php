<?php

echo "A faire... Eh oui j'ai pas eu le temps je suis désolé :(";


$values = ['-1', '0', '1'];
$colors = ['f87171', 'facc15', '22d3ee'];

# TODO: investiguer pourquoi j'ai besoin de get deux fois le [$idProposition]

foreach ($propositions as $proposition) {

    echo '<div class="flex flex-row justify-end">';

    $idProposition = $proposition->getIdProposition();
    $resultats = $resultats[$idProposition];
    $responsable = $responsables[$idProposition];


    echo '
    <div class="flex pr-3">
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2 items-center">
            <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '
        </div>
    </div>
    ';

    echo '<div class="proposition flex flex-row flex-grow items-center cursor-pointer">';

    // TODO afficher résultat avec le bon design

    for ($i = 0; $i < sizeof($values); $i++) {
        foreach ($resultats[$idProposition] as $key => $val) {
            if ($values[$i] == $key) {
                echo '
                <div class="flex h-8 items-center justify-center" style="width: ' . $val . '%; background-color: #' . $colors[$i] . '">
                    <p class="text-center text-white">' . $val . '%</p>
                </div>
                ';
            }
        }
    }
    echo '</div>';
    echo '</div>';
}