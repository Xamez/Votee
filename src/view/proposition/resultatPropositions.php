<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Controller\ControllerProposition;

echo '<script type="text/javascript" src="assets/js/resultatVote.js"></script>';

$resultatGagnant = $resultats[array_key_first($resultats)];
$resultatGagnant = $resultatGagnant[1];

ControllerProposition::afficheVue($voteUrl, ["propositions" => $propositions, "resultats" => $resultats, "resultatGagnant" => $resultatGagnant, "responsables" => $responsables, "sections" => $sections, "textes" => $textes]);

AbstractController::afficheVue('button.php', ['controller' => 'question', 'action' => 'readQuestion', 'params' => 'idQuestion=' . $rawIdQuestion, 'title' => 'Retour', "logo" => 'reply']);
?>
