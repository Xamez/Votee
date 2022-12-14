<?php

use App\Votee\Lib\ConnexionUtilisateur;

echo '<div class="flex items-center gap-2">
        <p class="text-main font-semibold">Organisateur : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($organisateur->getNom()) . ' ' . htmlspecialchars($organisateur->getPrenom()) .
        '</div>
        </p>
      </div>';

echo '<p><span class="text-main font-semibold">Période actuelle : </span>';
echo $question->getPeriodeActuelle() . '</p>';

echo '<h1 class="title text-dark text-2xl font-semibold">Organisation</h1><div>';
foreach ($sections as  $key=>$section) {
    echo '<p class="text-xl text-main font-bold">' . $key + 1  . ' - '
        . htmlspecialchars($section->getTitreSection()) . '</p>';
}

echo '</div><h1 class="title text-dark text-2xl font-semibold">Calendrier</h1>
        <p><span class="text-xl text-main font-bold text-lg">Période d\'écriture : </span> Du '. $question->getDateDebutQuestion().' au ' . $question->getDateFinQuestion() .'.</p>
        <p><span class="text-xl text-main font-bold text-lg">Période de vote : </span> Du '. $question->getDateDebutVote().' au ' . $question->getDateFinVote() .'.</p>';

echo '<h1 class="title text-dark text-2xl font-semibold">Proposition</h1>';
foreach ($propositions as $key=>$proposition) {
    echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition='. rawurlencode($proposition->getIdProposition()).'">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Proposition de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
                        htmlspecialchars($responsables[$key]->getNom()) . ' ' . htmlspecialchars($responsables[$key]->getPrenom()) .
                    '</div>
                 </div>
                 <div class="flex gap-2">';
    if ($proposition->getVisibilite() == 'invisible') echo '<span class="material-symbols-outlined">visibility_off</span>';
    echo '<span class="material-symbols-outlined">arrow_forward_ios</span>
          </div>
          </div>
          </a>';
}
echo '<div class="flex gap-2 justify-between">';
if (ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'organisateur') {
    echo
    '<div class="flex">
         <a href="./frontController.php?controller=question&action=updateQuestion&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
            <div class="flex gap-2">
                <p>Editer</p>
                <span class="material-symbols-outlined">edit</span>
            </div>
         </a>
    </div>';
}

$role = ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion());
if ($role == 'votant' || $role == 'organisateur') {
    echo '
    <div class="flex">
         <a href="./frontController.php?controller=proposition&action=voterPropositions&idQuestion='. rawurldecode($question->getIdQuestion()) .'">
            <div class="flex gap-2">
                <p>Voter pour tous</p>
                <span class="material-symbols-outlined">how_to_vote</span>
            </div>
         </a>
    </div>
    ';
}

echo '
    <div class="flex">
         <a href="./frontController.php?controller=proposition&action=createProposition&idQuestion='. rawurldecode($question->getIdQuestion()) .'">
            <div class="flex gap-2">
                <p>Demandes</p>
                <span class="material-symbols-outlined">file_copy</span>
            </div>
         </a>
    </div>
</div>';
