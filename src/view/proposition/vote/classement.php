<?php

echo '<div class="flex justify-center mx-auto gap-16">';

echo '
    <form class="p-3 rounded-xl text-white text-center" style="background-color: ' . (2 == $note ? "#22C55E" : "#8b5cf6") . '" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="2">
        <input type="hidden" name="" value="' . $isRedirected . '">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Choisir comme gagante" />
    </form>
    ';

echo '</div>';