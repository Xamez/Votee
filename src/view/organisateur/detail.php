<?php
echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Organisateur : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2"><span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($organisateur->getNom()) . ' ' . htmlspecialchars($organisateur->getPrenom()) .
    '</div></p></div>';

echo '<h1>Organisation</h1>';

echo '<h1>Calendrier</h1><p><span class="text-main font-bold text-lg">Période décriture : </span> Du '. $question->getDateDebutQuestion().' au ' . $question->getDateFinQuestion() .'.</p>';
echo '<p><span class="text-main font-bold text-lg">Période de vote : </span> Du '. $question->getDateDebutVote().' au ' . $question->getDateFinVote() .'.</p>';

echo '<h1>Proposition</h1>';
foreach ($propositions as $key=>$proposition) {
    echo '<a href="./frontController.php?action=proposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&login='. rawurlencode($proposition->getLogin()).'">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold">Proposition de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
                        htmlspecialchars($utilisateurs[$key]->getNom()) . ' ' . htmlspecialchars($utilisateurs[$key]->getPrenom()) .
                    '</div>
                 </div>
                 <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
         </a>';
}