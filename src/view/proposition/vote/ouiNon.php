<?php
echo '
<div class="flex justify-center mx-auto gap-16">

    <form class="p-3 rounded-xl w-28 text-white text-center cursor-pointer" style="background-color: #b8b8f8" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="-1">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Non" />
    </form>
    
    <form class="p-3 rounded-xl w-28 text-white text-center cursor-pointer" style="background-color: #aea4ff" method="post" action="frontController.php?controller=proposition&action=createdVote">
        <input type="hidden" name="idQuestion" value="' . rawurlencode($idQuestion) . '">
        <input type="hidden" name="idProposition" value="' . rawurlencode($idProposition) . '">
        <input type="hidden" name="idVotant" value="' . $idVotant . '">
        <input type="hidden" name="noteProposition" value="1">
        <input class="bg-transparent font-bold cursor-pointer" type="submit" value="Oui" />
    </form>
    
</div>
';