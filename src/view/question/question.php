<?php

use App\Votee\Model\DataObject\Periodes;

?>
<a href="./frontController.php?controller=question&action=readQuestion&idQuestion=<?=rawurlencode($question->getIdQuestion()) ?>">
    <div class="flex justify-between items-center bg-light p-2 rounded">
        <?= htmlspecialchars($question->getTitre()) ?>
        <div class="flex items-center gap-2">
            <div class="<?= ($question->getPeriodeActuelle() == Periodes::PREPARATION->value ? 'bg-main text-white' : 'bg-white')?> flex text-main shadow-md rounded-2xl w-fit p-1.5">
                <?= $question->getPeriodeActuelle() ?>
            </div>
            <span class="material-symbols-outlined">arrow_forward_ios</span>
        </div>
    </div>
</a>