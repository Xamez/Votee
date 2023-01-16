<div class="flex flex-col gap-8 mt-10">
    <div class="flex items-center justify-center sm:justify-start gap-2">
        <div class="headerProp grid items-center gap-5">
            <span class="text-main font-semibold w-28">Organisateur :</span>
            <a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=<?= rawurlencode($organisateur->getLogin()) ?>">
                <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span>
                    <?= htmlspecialchars($organisateur->getPrenom()) . ' ' . htmlspecialchars($organisateur->getNom()) ?>
                </div>
            </a>
            <?php
            if ($specialiste != null) {
                echo '<span class="text-main font-semibold">Spécialiste :</span>
                      <a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($specialiste->getLogin()) . '"
                            class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
                            htmlspecialchars($specialiste->getPrenom()) . ' ' . htmlspecialchars($specialiste->getNom()) . '
                      </a>';
            }
            ?>
        </div>
    </div>

    <div class="flex flex-col gap-3 rounded-xl py-4 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Plan</h1>
        <div class="px-7">
            <p class="break-words"><?= htmlspecialchars($question->getDescription()) ?></p>
        </div>
        <div class="p-7 flex flex-col gap-7">
            <?php

            use App\Votee\Controller\AbstractController;
            use App\Votee\Lib\ConnexionUtilisateur;
            use App\Votee\Model\DataObject\Periodes;
            use App\Votee\Model\DataObject\VoteTypes;
            use App\Votee\Model\Repository\PropositionRepository;
            use App\Votee\Model\Repository\VoteRepository;

            $rolesQuestion = ConnexionUtilisateur::getRolesQuestion($question->getIdQuestion());
            $idQuestion = rawurldecode($question->getIdQuestion());

            foreach ($sections as $key => $section) {
                echo '<div class="flex flex-col gap-3">
                         <p class="text-xl break-all text-main font-bold">' . $key + 1 . ' - '
                        . htmlspecialchars($section->getTitreSection()) . '
                         </p>
                         <span class="break-words">'. htmlspecialchars($section->getDescriptionSection()) . '</span>
                     </div>';
                }
            ?>
        </div>
    </div>
    <div class="flex flex-col gap-3 rounded-xl py-4 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Calendrier</h1>
        <div class="flex pt-4 gap-2 justify-center">
            <span class="text-main font-semibold">Période actuelle : </span>
            <span><?= $question->getPeriodeActuelle() ?></span>
        </div>
        <div class="flex items-center p-10">
            <?php
            $debutEcriture = $question->getDateDebutQuestion();
            $finEcriture = $question->getDateFinQuestion();
            $debutVote = $question->getDateDebutVote();
            $finVote = $question->getDateFinVote();
            $now = strtotime("now");
            $today = strtotime("today");
            $diffEcriture = strtotime(date('Y-m-d',$finEcriture)) - strtotime(date('Y-m-d',$debutEcriture));
            $widthEcriture = max(0, min((($today - strtotime(date('Y-m-d',$debutEcriture))) * 100 ) / $diffEcriture, 100));
            $diffVote = strtotime(date('Y-m-d',$finVote)) - strtotime(date('Y-m-d',$debutVote));
            $widthVote = max(0, min((($today - strtotime(date('Y-m-d',$debutVote))) * 100) / $diffVote, 100));
            echo '
            <div class="w-9 h-9 border-4 border-light rounded-3xl ' . ($now >= $debutEcriture ? 'bg-main' : 'bg-light') . ' ' . ($now < $debutEcriture && $today == strtotime(date("Y-m-d", $debutEcriture)) ? 'animPhase' : '') . ' flex flex-col items-center z-10 -m-2">
                <span class="font-semibold relative top-10">' . date('d/m/Y',$debutEcriture) . '</span>
            </div>
            <div class="bg-dark h-6 w-full relative">
                <span class="text-white absolute mix-blend-difference text-center select-none w-full absolute -translate-x-1/2">Période d\'écriture</span>
                <div class="bg-light h-6 ' . ($widthEcriture == 100 ? '' : 'rounded-r-lg') . '" style="width:' . $widthEcriture . '%"></div>
            </div>';
            $diffTransition = strtotime(date('Y-m-d',$debutVote)) - strtotime(date('Y-m-d', $finEcriture));
            if ($diffTransition != 0) {
                $diffTransition = strtotime(date('Y-m-d',$debutVote)) - strtotime(date('Y-m-d', $finEcriture));
                $widthTransition = max(0, min((($today - strtotime(date('Y-m-d',$finEcriture)))  * 100) / ($diffTransition == 0 ? 1 : $diffTransition), 100));
                echo '
            <div class="w-9 h-9 border-4 border-light rounded-3xl ' . ($now >= $finEcriture ? 'bg-main' : 'bg-light') . ' ' . ($now < $finEcriture && $today == strtotime(date("Y-m-d", $finEcriture)) ? 'animPhase' : '') . ' flex flex-col items-center z-10 -m-2">
                <span class="font-semibold relative top-10">' . date('d/m/Y',$finEcriture) . '</span>
            </div>
            <div class="bg-dark h-6 w-full relative">
                <span class="text-white absolute mix-blend-difference text-center select-none w-full absolute -translate-x-1/2">Période de transition</span>
                <div class="bg-light hover:h-8 h-6 ' . ($widthTransition == 100 ? '' : 'rounded-r-lg') . '" style="width:' . $widthTransition . '%"></div>
            </div>';
            }
            echo '
            <div class="w-9 h-9 border-4 border-light rounded-3xl ' . ($now >= $debutVote ? 'bg-main' : 'bg-light') . ' ' . ($now < $debutVote && $today == strtotime(date("Y-m-d", $debutVote)) ? 'animPhase' : '') . ' flex flex-col items-center z-10 -m-2">
                <span class="font-semibold relative top-10">' .  date("d/m/Y", $debutVote) . '</span>
            </div>
            <div class="bg-dark h-6 w-full relative">
                <span class="text-white absolute mix-blend-difference text-center select-none w-full absolute -translate-x-1/2">Période de vote</span>
                <div class="bg-light h-6 ' . ($widthVote == 100 ? '' : 'rounded-r-lg') . '" style="width:' . $widthVote . '%"></div>
            </div>
            
            <div class="w-9 h-9 border-4 border-light rounded-3xl ' . ($now >= $finVote ? 'bg-main' : 'bg-light') . ' ' . ($now < $finVote && $today == strtotime(date("Y-m-d", $finVote)) ? 'animPhase' : '') . ' flex flex-col items-center z-10 -m-2">
                <span class="font-semibold relative top-10">' . date("d/m/Y", $finVote) . '</span>
            </div>';
            ?>
        </div>
        <?php
        if (in_array('Organisateur', ConnexionUtilisateur::getRolesQuestion($idQuestion)) &&
            (($now < $debutEcriture && $today == strtotime(date("Y-m-d", $debutEcriture))) ||
            ($now < $finEcriture && $today == strtotime(date("Y-m-d", $finEcriture))) ||
            ($now < $debutVote && $today == strtotime(date("Y-m-d", $debutVote))) ||
            ($now < $finVote && $today == strtotime(date("Y-m-d", $finVote))))) {
            echo '<div class="flex justify-center w-full mt-10">
                    <a href="./frontController.php?controller=question&action=changePhase&idQuestion=' . $idQuestion . '" class="w-60 cursor-pointer p-2 text-white bg-main font-semibold rounded-lg text-center">Passer à la nouvelle phase</a>
                  </div>';
        }
        ?>
    </div>
    <div class="flex flex-col gap-3 rounded-xl py-4 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Proposition</h1>
        <div class="flex flex-col gap-3 p-7">
<?php

if ($question->getPeriodeActuelle() == Periodes::RESULTAT->value) {
    $resultats = (new VoteRepository())->getResultats($question);
    $propositionsGagnantes = (new VoteRepository())->getPropositionsGagantes($question, $resultats);
    if (sizeof($rolesQuestion) > 0) {
        foreach ($resultats as $idProposition => $ignored) {
            $proposition = (new PropositionRepository())->select($idProposition);
            if ($proposition->isVisible()) {
                echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . $idQuestion . '&idProposition=' . rawurlencode($idProposition) . '">
                          <div class="flex flex-col bg-light justify-between p-2 items-center rounded md:flex-row">
                              <div class="flex flex-col items-center gap-2 md:flex-row">
                                  <p class="font-bold text-dark hidden md:block">Proposition de : </p>
                                  <div class="' . (in_array($idProposition, $propositionsGagnantes) ? "bg-green-400 text-white" : "bg-white text-main") . ' flex gap-1 shadow-md rounded-2xl w-fit p-2">
                                      <span class="material-symbols-outlined">' . (in_array($idProposition, $propositionsGagnantes) ? "military_tech" : "account_circle") . '</span>' . htmlspecialchars($responsables[$idProposition]->getPrenom()) . ' ' . htmlspecialchars($responsables[$idProposition]->getNom()) . '
                                  </div>
                                  <span class="text-ellipsis overflow-hidden whitespace-nowrap">' . htmlspecialchars($proposition->getTitreProposition()) . '</span>
                              </div>
                              <span class="material-symbols-outlined">arrow_forward_ios</span>
                          </div>
                      </a>';
            } else {
                if (count(array_intersect(['CoAuteur', 'Responsable'], $rolesQuestion)) > 0 || in_array("Organisateur", $rolesQuestion)) {
                    AbstractController::afficheVue('question/proposition.php', ['idQuestion' => $idQuestion, 'idProposition' => rawurlencode($idProposition), "responsable" => $responsables[$idProposition], "proposition"=>$proposition]);
                }
            }
        }
    } else {
        foreach ($propositionsGagnantes as $idProposition) {
            $proposition = (new PropositionRepository())->select($idProposition);
            echo '<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=' . $idQuestion . '&idProposition=' . rawurlencode($idProposition) . '">
                  <div class="flex flex-col bg-light justify-between p-2 items-center rounded md:flex-row">
                      <div class="flex flex-col items-center gap-2 md:flex-row">
                          <p class="font-bold text-dark hidden md:block">Proposition de : </p>
                          <div class="bg-green-400 text-white flex gap-1 shadow-md rounded-2xl w-fit p-2">
                              <span class="material-symbols-outlined">military_tech</span>' . htmlspecialchars($responsables[$idProposition]->getPrenom()) . ' ' . htmlspecialchars($responsables[$idProposition]->getNom()) . '
                          </div>
                          <span class="text-ellipsis overflow-hidden whitespace-nowrap">' . htmlspecialchars($proposition->getTitreProposition()) . '</span>
                      </div>
                      <span class="material-symbols-outlined">arrow_forward_ios</span>
                  </div>
              </a>';
        }
    }
} else {
    $nbPropInvisibleUtil = 0; // Nombre de propositions invisible pour l'utilisateur (en tenant compte de ses roles)
    $nbPropInvisible = 0; // Nombre de propositions invisible dans la question
    foreach ($propositions as $proposition) {
        $idProposition = $proposition->getIdProposition();
        $roles = ConnexionUtilisateur::getRolesProposition($idProposition);
        if ($proposition->isVisible()) {
            AbstractController::afficheVue('question/proposition.php', ['idQuestion' => $idQuestion, 'idProposition' => rawurlencode($idProposition), "responsable" => $responsables[$idProposition], "proposition"=>$proposition]);
        } else {
            $nbPropInvisible++;
            if (count(array_intersect(['CoAuteur', 'Responsable'], $roles)) > 0 || in_array("Organisateur", $rolesQuestion)) {
                AbstractController::afficheVue('question/proposition.php', ['idQuestion' => $idQuestion, 'idProposition' => rawurlencode($idProposition), "responsable" => $responsables[$idProposition], "proposition"=>$proposition]);
            } else $nbPropInvisibleUtil++;
        }
    }
    if (sizeof($propositions) == 0 || (($nbPropInvisibleUtil == $nbPropInvisible) && $nbPropInvisible > 0) && sizeof($propositions) == 0) echo '<span class="text-center">Aucune proposition</span>';
}



echo '</div>
      </div>
      <div class="flex flex-col gap-3 rounded-xl py-4 bg-lightPurple">
         <h1 class="title text-dark text-2xl font-semibold">Votants</h1>
             <div class="flex pt-4 gap-2 justify-center">
                <span class="text-main font-semibold">Type de vote : </span>
                <span>' . VoteTypes::getFromKey($question->getVoteType())->value . '</span>
            </div>
            <div class="flex flex-wrap gap-2 justify-center gap-2 p-7">';
if (sizeof($groupesVotants) == 0) echo '<span class="text-center">Aucun votant</span>';
foreach ($groupesVotants as $key => $groupeVotant) {
    if (trim($key, " 0..9") == 'votant') {
        echo '<a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($groupeVotant->getLogin()) . '">
                <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($groupeVotant->getPrenom()) . ' ' . htmlspecialchars($groupeVotant->getNom()) . '
                </div>
              </a>';
    } else {
        echo '<a href="./frontController.php?controller=groupe&action=readGroupe&idGroupe=' . rawurlencode($groupeVotant->getIdGroupe()) . '">
                <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">group</span>' . htmlspecialchars($groupeVotant->getNomGroupe()) . '
                </div>
              </a>';
    }
}
$rawIdQuestion = rawurlencode($question->getIdQuestion());
if ($size > 10) echo '<a class="flex items-center gap-2 p-2 text-white bg-main font-semibold rounded-2xl" href="./frontController.php?controller=question&action=readVotant&idQuestion=' . $rawIdQuestion . '">
                        <span class="material-symbols-outlined">more_horiz</span>Voir plus
                      </a>';
echo '    </div>
      </div>
      <div class="flex gap-2 justify-between flex-col md:flex-row">';

AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'all', 'title' => 'Retour', "logo" => 'reply']);
if ($question->getPeriodeActuelle() == Periodes::ECRITURE->value || $question->getPeriodeActuelle() == Periodes::PREPARATION->value) {
    if (in_array("Organisateur", $rolesQuestion)) {
        AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'updateQuestion', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Editer', "logo" => 'edit']);
        echo '<div class="flex bg-white border-lightPurple text-main">
                <a class="button w-full border-2 border-r-0 border-lightPurple rounded-l-3xl hover:text-white" href="./frontController.php?controller=question&action=addVotant&idQuestion=' . $rawIdQuestion . '">
                    <div class="p-2 flex gap-2 justify-center">
                        <span class="material-symbols-outlined">manage_accounts</span>
                        <p>Votants</p>
                    </div>
                </a>
                <div class="bg-purple w-1 opacity-75 h-full" ></div>
                <a class="button w-full border-2 border-l-0 border-lightPurple rounded-r-3xl hover:text-white" href="./frontController.php?controller=question&action=addResp&idQuestion=' . $rawIdQuestion . '">
                    <div class="p-2 flex gap-2 justify-center">
                        <span class="material-symbols-outlined">manage_accounts</span>
                        <p>Responsables</p>
                    </div>
                </a>
              </div>';
        AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'deleteQuestion', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Supprimer', "logo" => 'delete']);
    }
    if ($question->getPeriodeActuelle() == Periodes::ECRITURE->value && !ConnexionUtilisateur::hasPropositionVisible($question->getIdQuestion())) {
        if (!$isDemande) {
            if (ConnexionUtilisateur::creerProposition($idQuestion) || in_array("Organisateur", $rolesQuestion)) {
                AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'createProposition', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Créer une proposition', "logo" => 'add_circle']);
            } else {
                AbstractController::afficheVue('button.php', ['controller' => 'demande', 'action' => 'createDemande', 'params' => 'titreDemande=proposition&idQuestion=' . $rawIdQuestion, 'title' => 'Faire une demande', "logo" => 'file_copy']);
            }
        } else {
            AbstractController::afficheVue('button.php', ['controller' => 'utilisateur', 'action' => 'historiqueDemande', 'title' => 'Voir ma demande', "logo" => 'info']);
        }
    }
}

if (sizeof($propositions) > 0) {
    if ($question->getPeriodeActuelle() == Periodes::VOTE->value) {
        if (count(array_intersect(['Votant', 'Organisateur', 'Responsable'], $rolesQuestion)) > 0) {
            AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'voterPropositions', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Voter pour tous', "logo" => 'how_to_vote']);
        }
    } else if ($question->getPeriodeActuelle() == Periodes::RESULTAT->value) {
        AbstractController::afficheVue('button.php', ['controller' => 'proposition', 'action' => 'resultatPropositions', 'params' => 'idQuestion=' . $idQuestion, 'title' => 'Voir les résultats', "logo" => 'list_alt']);
    }
}
echo '</div>
    </div>';