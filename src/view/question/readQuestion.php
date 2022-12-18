<?php

use App\Votee\Lib\ConnexionUtilisateur;
$rolesQuestion = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
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
      <h1 class="title text-dark text-2xl font-semibold">Proposition</h1>
      ';

foreach ($propositions as $proposition) {
    $idProposition = $proposition->getIdProposition();
    $roles = ConnexionUtilisateur::getRolesProposition($idProposition);

    if ($proposition->isVisible()) {
        echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition='. rawurlencode($idProposition).'">
                <div class="flex bg-light justify-between p-2 items-center rounded">
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-dark">Proposition de : </p>
                        <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                            <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$idProposition]->getPrenom   ()) . ' ' . htmlspecialchars($responsables[$idProposition]->getNom()) . '
                        </div>
                    </div>
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
          </a>';
    } else {
        if (count(array_intersect(['CoAuteur', 'Responsable'], $rolesQuestion)) > 0 || in_array("Organisateur", $rolesQuestion)) {
            echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . rawurlencode($question->getIdQuestion()) . '&idProposition='. rawurlencode($idProposition).'">
                    <div class="flex bg-light justify-between p-2 items-center rounded">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-dark">Proposition de : </p>
                            <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                                <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$idProposition]->getPrenom()) . ' ' . htmlspecialchars($responsables[$idProposition]->getNom()) . '
                            </div>
                        </div>
                        <div class="flex gap-2">';
            if (!$proposition->isVisible()) echo '<span class="material-symbols-outlined">visibility_off</span>';
            echo '<span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
          </a>';
        }
    }

}
if ($question->getPeriodeActuelle() == 'Période d\'écriture') {
    echo '<div class="flex gap-2 justify-between">';
    if (in_array("Organisateur", $rolesQuestion)) {
        echo '<div class="flex justify-start">
                 <a href="./frontController.php?controller=question&action=updateQuestion&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
                    <div class="flex gap-2">
                        <p>Editer</p>
                        <span class="material-symbols-outlined">edit</span>
                    </div>
                 </a>
              </div>';
    }
    if (!in_array("Responsable", $rolesQuestion)) {
        echo '<div class="flex justify-end">';
        if (ConnexionUtilisateur::estConnecte()) {
            if (ConnexionUtilisateur::creerProposition($question->getIdQuestion())) {
                echo '<a href="./frontController.php?controller=proposition&action=createProposition&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">            
                        <div class="flex gap-2">
                            <p>Créer une proposition</p>
                            <span class="material-symbols-outlined">add_circle</span>
                        </div>
                      </a>';
            } else {
                echo '<a href="./frontController.php?controller=demande&action=createDemande&titreDemande=proposition&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
                        <div class="flex gap-2">
                            <p>Faire une demande</p>
                            <span class="material-symbols-outlined">file_copy</span>
                        </div>
                     </a>';
            }
        }
        echo '</div>';
    }
}

if (sizeof($propositions) > 0) {
    if ($question->getPeriodeActuelle() == 'Période de vote') {
        // TODO : GERER L ORDRE DES ROLES ET VIRER REPR
        if (count(array_intersect(['Votant', 'Organisateur', 'Responsable'], $rolesQuestion)) > 0) {
            echo '
            <div class="flex">
                 <a href="./frontController.php?controller=proposition&action=voterPropositions&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
                    <div class="flex gap-2">
                        <p>Voter pour tous</p>
                        <span class="material-symbols-outlined">how_to_vote</span>
                    </div>
                 </a>
            </div>
            ';
        }
    } else if ($question->getPeriodeActuelle() == 'Période des résultats') {
        echo '
            <div class="flex">
                 <a href="./frontController.php?controller=proposition&action=resultatPropositions&idQuestion=' . rawurldecode($question->getIdQuestion()) . '">
                    <div class="flex gap-2">
                        <p>Voir les résultats</p>
                        <span class="material-symbols-outlined">list_alt</span>
                    </div>
                 </a>
            </div>
            ';
    }
}


echo '</div>';