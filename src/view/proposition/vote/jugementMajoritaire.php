<?php

echo '<div class="flex justify-center mx-auto">';

$colors = ["c6c6f4", "b8b8f8", "aea4ff", "8080d7"];
$values = ["-2", "-1", "1", "2"];
$labels = ["Insuffisant", "Passable", "Bien", "Très Bien"];

for ($i = 0; $i < 4; $i++) {
    if ($note == 0) {
        echo '
        <form class="p-3 rounded-xl w-28' . ($i > 0 ? " -ml-3.5 " : " ") . 'text-white text-center cursor-pointer" style="background-color: #' . $colors[$i] . '" method="post" action="frontController.php?controller=proposition&action=createdVote">
            <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
            <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
            <input type="hidden" name="idVotant" value="' . $idVotant . '">
            <input type="hidden" name="noteProposition" value="' . $values[$i] . '">
            <input type="hidden" name="" value="' . $isRedirected . '"
            <input class="bg-transparent font-bold cursor-pointer" type="submit" value="' . $labels[$i] . '" />
        </form>
        ';
    } else {
        echo '
        <div class="p-3 rounded-xl w-28' . ($i > 0 ? " -ml-3.5 " : " ") . 'text-white text-center" style="background-color: #' . ($values[$i] == $note ? "22C55E" : $colors[$i]) . '">
            <p class="bg-transparent font-bold">' . $labels[$i] . '</p>
        </div>
        ';
    }
}

echo '</div>';