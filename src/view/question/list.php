<?php
echo '<div class="flex flex-col gap-3">';
foreach ($questions as $question) {
    $questionTitreHTML = htmlspecialchars($question->getTitre());
    $questionIdURL = rawurlencode($question->getIdQuestion());

    echo '<a href="./frontController.php?action=read&idQuestion=' . $questionIdURL . '">
        <div class="flex justify-between bg-light p-2 rounded">' . $questionTitreHTML . '<span class="material-symbols-outlined">arrow_forward_ios</span></div></a>';
}
echo '</div><a class="w-36 flex p-2 justify-center text-white bg-main font-semibold rounded-lg" href="./frontController.php?action=create">Créer un vote</a>';



