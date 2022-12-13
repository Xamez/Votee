<?php
echo '
<div class="flex">
    <div class="p-3 rounded-l-xl w-28 text-white font-semibold" style="background-color: #b8b8f8">
        <a href="./frontController.php?action=createdVote&idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition) . '&idVotant=' . $idVotant . '&value=-1">Non</a>
    </div>
    <div class="p-3 rounded-xl w-28 -ml-3.5 text-white font-semibold text-center" style="background-color: #aea4ff">
        <a href="./frontController.php?action=createdVote&idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition) . '&idVotant=' . $idVotant . '&value=1">Oui</a>
    </div>
</div>
';