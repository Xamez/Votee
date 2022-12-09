<?php
echo '<div class="flex flex-col gap-3">';
if($questions) {
    foreach ($questions as $question) {
        $questionTitreHTML = htmlspecialchars($question->getTitre());
        $questionIdURL = rawurlencode($question->getIdQuestion());

        echo '
        <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . $questionIdURL . '">
        <div class="flex justify-between items-center bg-light p-2 rounded">
            ' . $questionTitreHTML . '
            <div class="flex items-center gap-2">
                <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">
                            ' . $question->getPeriodeActuelle() . '
                </div>
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
        </div>
        </a>';
    }
}
echo '</div><a class="w-36 flex p-2 justify-center text-white bg-main font-semibold rounded-lg" href="./frontController.php?controller=question&action=section">Créer un vote</a>';
