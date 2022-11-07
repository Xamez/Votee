<?php
echo '<div class="flex items-center gap-2">
        <p class="text-main font-semibold">Organisateur : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($organisateur->getNom()) . ' ' . htmlspecialchars($organisateur->getPrenom()) .
        '</div>
        </p>
      </div>';

echo '<p><span class="text-main font-semibold">Période actuelle : </span>';
$date = date('d/m/y');
if ($date <= $question->getDateFinQuestion()) {
    echo 'Période d’écriture.</p>';
} elseif ($date > $question->getDateFinQuestion() && $date <= $question->getDateFinVote()) {
    echo 'Période de vote.</p>';
} elseif ($date > $question->getDateFinVote()) {
    echo 'Période terminée.</p>';
}

echo '<h1 class="text-2xl font-bold text-center text-dark">Organisation</h1><div>';
foreach ($sections as  $key=>$section) {
    echo '<p class="text-xl text-main font-bold">' . $key +1  . ' - ' . $section->getTitreSection() . '</p>';
}

echo '</div><h1 class="text-2xl font-bold text-center text-dark">Calendrier</h1>
        <p><span class="text-xl text-main font-bold text-lg">Période décriture : </span> Du '. $question->getDateDebutQuestion().' au ' . $question->getDateFinQuestion() .'.</p>
        <p><span class="text-xl text-main font-bold text-lg">Période de vote : </span> Du '. $question->getDateDebutVote().' au ' . $question->getDateFinVote() .'.</p>';

echo '<h1 class="text-2xl font-bold text-center text-dark">Proposition</h1>';
foreach ($propositions as $key=>$proposition) {
    echo '<a href="./frontController.php?action=readProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition='. rawurlencode($proposition->getIdProposition()).'">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Proposition de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
                        htmlspecialchars($responsables[$key]->getNom()) . ' ' . htmlspecialchars($responsables[$key]->getPrenom()) .
                    '</div>
                 </div>
                 <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
         </a>';
}

echo '<div class="flex justify-end">
         <a href="./frontController.php?action=createProposition&idQuestion='. $question->getIdQuestion() .'">
            <div class="flex gap-2">
                <p>Demandes</p>
                <span class="material-symbols-outlined">file_copy</span>
            </div>
         </a>
      </div>';