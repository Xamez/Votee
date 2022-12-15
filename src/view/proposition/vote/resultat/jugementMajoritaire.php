<?php

$colors = ['', '', '', ''];

echo '<div class="">';

foreach ($propositions as $proposition) {
    $idProposition = $proposition->getIdProposition();
    echo '<div class="flex flex-row">';
    for ($i = 0; $i < sizeof($values[$idProposition]); $i++) {
        echo '
        <div class="" style="width: ' . $values[$idProposition][$i] . '%">
            
        </div>
    ';
    }
    echo '</div>';
}

echo '</div>';