<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Lib\ConnexionUtilisateur;

$rolesQuestion = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
$idQuestion = rawurldecode($question->getIdQuestion());
echo '<div class="flex flex-col gap-10 mt-10">';
echo '<div class="flex flex-col gap-3">
      <div class="flex items-center gap-2">
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
      </div>
      <div class="flex flex-col gap-3">
      <h1 class="title text-dark text-2xl font-semibold">Organisation</h1>';

foreach ($sections as  $key=>$section) {
    echo '<p class="text-xl text-main font-bold">' . $key + 1  . ' - '
            . htmlspecialchars($section->getTitreSection()) . '
          </p>';
}

echo '</div>
      <div class="flex flex-col gap-3">
          <h1 class="title text-dark text-2xl font-semibold">Calendrier</h1>
          <p>
            <span class="text-xl text-main font-bold text-lg">Période d\'écriture : </span> 
            Du '. $question->getDateDebutQuestion().' au ' . $question->getDateFinQuestion() .'
          </p>
          <p>
            <span class="text-xl text-main font-bold text-lg">Période de vote : </span> 
            Du '. $question->getDateDebutVote().' au ' . $question->getDateFinVote() .'
          </p>
      </div>
      <div class="flex flex-col gap-3">
      <h1 class="title text-dark text-2xl font-semibold">Proposition</h1>';

if (sizeof($propositions) == 0) echo '<span class="text-center">Aucune proposition</span>';
foreach ($propositions as $proposition) {
    $idProposition = $proposition->getIdProposition();
    $roles = ConnexionUtilisateur::getRolesProposition($idProposition);

    if ($proposition->isVisible()) {
        echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . $idQuestion . '&idProposition='. rawurlencode($idProposition).'">
                <div class="flex flex-col bg-light justify-between p-2 items-center rounded md:flex-row">
                        <div class="flex flex-col items-center gap-2 md:flex-row">
                        <p class="font-bold text-dark hidden md:block">Proposition de : </p>
                        <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                            <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$idProposition]->getPrenom   ()) . ' ' . htmlspecialchars($responsables[$idProposition]->getNom()) . '
                        </div>
                        <span>' . htmlspecialchars($proposition->getTitreProposition()) . '</span>
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
              </a>';
    } else {
        if (count(array_intersect(['CoAuteur', 'Responsable'], $rolesQuestion)) > 0 || in_array("Organisateur", $rolesQuestion)) {
            echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . $idQuestion . '&idProposition='. rawurlencode($idProposition).'">
                    <div class="flex bg-light justify-between p-2 items-center rounded">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-dark">Proposition de : </p>
                            <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                                <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$idProposition]->getPrenom()) . ' ' . htmlspecialchars($responsables[$idProposition]->getNom()) . '
                            </div>
                            <span>' . htmlspecialchars($proposition->getTitreProposition()) . '</span>
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
echo '</div>
      <div class="flex flex-col gap-3">
         <h1 class="title text-dark text-2xl font-semibold">Votants</h1>
            <div class="flex flex-wrap gap-2 justify-center">';
if (sizeof($groupesVotants) == 0) echo '<span class="text-center">Aucun votant</span>';
foreach ($groupesVotants as $key=>$groupeVotant) {
    if (trim($key, " 0..9") == 'votant') {
        echo '<div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($groupeVotant->getPrenom()) . ' ' . htmlspecialchars($groupeVotant->getNom()) . '
              </div>';
    } else {
        echo '<div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                <span class="material-symbols-outlined">group</span>' . htmlspecialchars($groupeVotant->getNomGroupe()). '
              </div>';
    }
}
if ($size > 10) echo '<a class="flex items-center gap-2 p-2 text-white bg-main font-semibold rounded-2xl" href="./frontController.php?controller=question&action=readVotant&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
                        <span class="material-symbols-outlined">more_horiz</span>Voir plus
                      </a>';
echo '</div>
      <div class="flex gap-2 justify-between">';
AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'all', 'title' => 'Retour', "logo" => 'reply']);
if ($question->getPeriodeActuelle() == 'Période d\'écriture') {
    if (in_array("Organisateur", $rolesQuestion)) {
        AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'updateQuestion', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Editer', "logo" => 'edit']);
        AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'addVotant', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Votants', "logo" => 'manage_accounts']);
    }
    if (!ConnexionUtilisateur::questionValide($question->getIdQuestion())) {
        if (ConnexionUtilisateur::creerProposition($question->getIdQuestion())) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'createProposition', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Créer une proposition', "logo" => 'add_circle']);
        } else {
            AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=proposition&idQuestion=' . $idQuestion, 'title' => 'Faire une demande', "logo" => 'file_copy']);
        }
    }
}

if (sizeof($propositions) > 0) {
    if ($question->getPeriodeActuelle() == 'Période de vote') {
        // TODO : GERER L ORDRE DES ROLES ET VIRER REPR
        if (count(array_intersect(['Votant', 'Organisateur', 'Responsable'], $rolesQuestion)) > 0) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'voterPropositions', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Voter pour tous', "logo" => 'how_to_vote']);
        }
    } else if ($question->getPeriodeActuelle() == 'Période des résultats') {
        AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'resultatPropositions', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Voir les résultats', "logo" => 'list_alt']);
    }
}
echo '</div>
     </div>
    </div>';