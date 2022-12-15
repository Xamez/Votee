<?php

$colors = ['red-400', 'orange-400', 'yellow-400', 'green-400', 'cyan-400'];

echo '<div class="">';

foreach ($propositions as $proposition) {
    $idProposition = $proposition->getIdProposition();
    echo '<div class="flex flex-row">';
    for ($i = 0; $i < sizeof($resultats[$idProposition]); $i++) {
        echo '
        <div class="" style="width: ' . $resultats[$idProposition][$i] . '%">
            
        </div>
        ';
    }
    echo '</div>';
}

echo '</div>';

