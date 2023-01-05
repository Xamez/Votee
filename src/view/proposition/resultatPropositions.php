<?php

use App\Votee\Controller\ControllerProposition;

echo '<script type="text/javascript" src="assets/js/resultatVote.js"></script>';
ControllerProposition::afficheVue($voteUrl, ["propositions" => $propositions, "resultats" => $resultats, "responsables" => $responsables, "sections" => $sections, "textes" => $textes]);
?>

<div class="flex gap-2 justify-between">
     <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=<?=rawurlencode($idQuestion)?>">
        <div class="flex gap-2">
            <span class="material-symbols-outlined">reply</span>
            <p>Retour</p>
        </div>
    </a>
<div>