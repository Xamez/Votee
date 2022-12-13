<?php
echo '
<div class="flex justify-center mx-auto">
    
    <form class="p-3 rounded-l-xl w-28 text-white cursor-pointer" style="background-color: #c6c6f4" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="-2">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Insuffisant" />
    </form>
    
    <form class="p-3 rounded-l-xl w-28 -ml-3.5 text-white text-center cursor-pointer" style="background-color: #b8b8f8" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="-1">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Passable" />
    </form>
    
    <form class="p-3 rounded-l-xl w-28 -ml-3.5 text-white text-center cursor-pointer" style="background-color: #aea4ff" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="1">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Bien" />
    </form>

    <form class="p-3 rounded-xl w-28 -ml-3.5 text-white text-center cursor-pointer" style="background-color: #8080d7" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="2">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Très Bien" />
    </form>

</div>
';