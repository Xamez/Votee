<?php

echo '<div class="flex gap-2 flex-wrap justify-center items-center">';

$values = ['-3', '-2', '-1', '1', '2', '3'];
$colors_select = ['ef4444', 'f97316', 'facc15', '22D3EE', '34D399', '22c55e']; // [red-500, orange-500, yellow-400, cyan-400, emerald-400, green-500]
$colors_unselect = ['c4b5fd60', 'c4b5fd', 'a78bfa', '8b5cf6', '7c3aed', '9333ea'];
$labels = ['A rejeter', 'Insuffisant', 'Passable', 'Assez bien', 'Bien', 'Très Bien'];

for ($i = 0; $i < sizeof($labels); $i++) {
    echo '
    <form class="p-3 rounded-xl w-28 text-white text-center" style="background-color: #' . ($values[$i] == $note ? $colors_select[$i] : $colors_unselect[$i]) . '" method="post" action="frontController.php?controller=proposition&action=createdVote">
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
