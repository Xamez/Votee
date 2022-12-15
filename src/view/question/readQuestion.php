<?php

use App\Votee\Lib\ConnexionUtilisateur;
$roleQuestion = ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion());

echo '<div class="flex items-center gap-2">
        <p class="text-main font-semibold">Organisateur : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($organisateur->getPrenom()) . ' ' . htmlspecialchars($organisateur->getNom()) .
        '</div>
        </p>
      </div>
      <p>
        <span class="text-main font-semibold">Période actuelle : </span>' .
            $question->getPeriodeActuelle() . '
      </p>
      <h1 class="title text-dark text-2xl font-semibold">Organisation</h1>
      <div>';

foreach ($sections as  $key=>$section) {
    echo '<p class="text-xl text-main font-bold">' . $key + 1  . ' - '
            . htmlspecialchars($section->getTitreSection()) . '
          </p>';
}

echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Calendrier</h1>
      <p>
        <span class="text-xl text-main font-bold text-lg">Période d\'écriture : </span> 
        Du '. $question->getDateDebutQuestion().' au ' . $question->getDateFinQuestion() .'
      </p>
      <p>
        <span class="text-xl text-main font-bold text-lg">Période de vote : </span> 
        Du '. $question->getDateDebutVote().' au ' . $question->getDateFinVote() .'
      </p>
      <h1 class="title text-dark text-2xl font-semibold">Proposition</h1>';
foreach ($propositions as $key=>$proposition) {
    $roleProposition = ConnexionUtilisateur::getRoleProposition($proposition->getIdProposition());

    if ($proposition->getVisibilite() == 'visible') {
        echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition='. rawurlencode($proposition->getIdProposition()).'">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Proposition de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
            htmlspecialchars($responsables[$key]->getNom()) . ' ' . htmlspecialchars($responsables[$key]->getPrenom()) .
            '   </div>
                </div>
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
          </a>';
    } else {
        if ($roleProposition == 'representant' || $roleProposition == 'coauteur' || $roleQuestion == 'organisateur') {
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
    }

}
echo '<div class="flex gap-2 justify-between">';
if ($roleQuestion == 'organisateur') {
    echo '<div class="flex justify-start">
         <a href="./frontController.php?controller=question&action=updateQuestion&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
            <div class="flex gap-2">
                <p>Editer</p>
                <span class="material-symbols-outlined">edit</span>
            </div>
         </a>
      </div>';
}

if ($roleQuestion != 'representant') {
    echo '<div class="flex justify-end">';
    if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::creerProposition($question->getIdQuestion())) {
        echo '<a href="./frontController.php?controller=proposition&action=createProposition&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">            
            <div class="flex gap-2">
                <p>Créer une proposition</p>
                <span class="material-symbols-outlined">add_circle</span>
            </div>
          </a>';
    } else if (ConnexionUtilisateur::estConnecte() && !ConnexionUtilisateur::creerProposition($question->getIdQuestion())) {
        echo '<a href="./frontController.php?controller=demande&action=createDemande&titreDemande=proposition&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
            <div class="flex gap-2">
                <p>Faire une demande</p>
                <span class="material-symbols-outlined">file_copy</span>
            </div>
         </a>';
    }
    echo '</div>';
}
echo '</div>';