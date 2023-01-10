<?php

echo '<div class="flex justify-center mx-auto gap-16">';

$colors = ['b8b8f8', 'aea4ff'];
$values = ['-1', '1'];
$labels = ['Non', 'Oui'];

for ($i = 0; $i < 2; $i++) {
    echo '
    <form class="p-3 rounded-xl w-28 text-white text-center" style="background-color: #' . ($values[$i] == $note ? "22C55E" : $colors[$i]) . '" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="' . $values[$i] . '">
        <input type="hidden" name="" value="' . $isRedirected . '">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="' . $labels[$i] . '" />
    </form>
    ';
}

echo '</div>';