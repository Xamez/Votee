<?php

use App\Votee\Controller\ControllerProposition;
echo '<script type="text/javascript" src="assets/js/accordion.js"></script>';

ControllerProposition::afficheVue($voteUrl, ["propositions" => $propositions, "resultats" => $resultats, "responsables" => $responsables]);
?>
<div class="flex gap-2 justify-between">
     <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=<?=rawurlencode($idQuestion) ?>">
        <div class="flex gap-2">
            <span class="material-symbols-outlined">reply</span>
            <p>Retour</p>
        </div>
    </a>
</div>