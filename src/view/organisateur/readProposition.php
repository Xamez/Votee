<?php
require "propositionHeader.php";
echo '</p><div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
foreach ($sections as $index=>$section) {
    $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
    $sectionDescHTML = htmlspecialchars($textes[$index]->getTexte());

    echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
              <p class="break-all text-justify">' . $sectionDescHTML . '</p>';
}
echo '</div>
        <div class="flex gap-2 justify-between">
            <a href="./frontController.php?action=updateProposition&idQuestion=' . rawurlencode($question->getIdQuestion()). '&idProposition='. rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    <p>Editer</p>
                </div
            </a>
            <a href="./frontController.php?action=selectFusion&idQuestion=' . rawurlencode($question->getIdQuestion()). '&idProposition='. rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined">upload</span>
                    <p>Fusionner</p>
                </div
            </a>
            <a href="./frontController.php?action=deleteProposition&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <p>Supprimer</p>
                    <span class="material-symbols-outlined">delete</span>
                </div>
            </a>
       </div>';
if ($question->getPeriodeActuelle() == 'Période de vote'){

    if ($question->getTypeVote() == 'VoteMajoritaire' ) {
        echo '
        <div>
        <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '&value=' . rawurldecode(-2) . '">Insuffisant</a>
        </div>
          <div>
           <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '&value=' . rawurldecode(-1) . '">Passable</a>
         </div>
         <div>
           <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '&value=' . rawurldecode(+1) . '">Bien</a>
         </div>
         <div>
           <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition=' . rawurlencode($idProposition) . '&value=' . rawurldecode(+2) . '">Très Bien</a>
         </div>
        ';
    }
}