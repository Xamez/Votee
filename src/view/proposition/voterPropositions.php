<?php
echo '<script type="text/javascript" src="assets/js/accordion.js"></script>';

echo '
<div class="accordion text-left w-full p-2 cursor-pointer flex justify-between p-2 items-center rounded">
    <div class="flex items-center gap-2">
        <p class="font-bold text-dark">Section ' . $index + 1 . ' de : </p>
        <div class="bg-white items-center flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$indexTexte]->getNom()) . ' ' . htmlspecialchars($responsables[$indexTexte]->getPrenom()) . '
        </div>
    </div>
    <span class="accordion-arrow material-symbols-outlined">arrow_forward_ios</span>
</div>     
';