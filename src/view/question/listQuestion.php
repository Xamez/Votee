<?php

use App\Votee\Controller\AbstractController;

$categories = ["Organisateur" => $questionsOrga, "Responsable" => $questionsRepre, "CoAuteur" => $questionsCoau, "Votant" => $questionsVota, "Specialiste" => $questionsSpecia];

echo '<div class="flex flex-col gap-10 mt-10">';
foreach ($categories as $key=>$category) {
    echo '<h1 class="title text-dark text-2xl font-semibold">' . $key . '</h1>
          <div class="flex flex-col gap-3">';
    foreach ($category as $question) {
        AbstractController::afficheVue('question/question.php', ['question' => $question]);
    }
    if (!$category) echo '<span class="text-center">Vous n\'avez pas de question en cours</span>';
    echo '</div>';
}
echo '</div>';
