<?php

use App\Votee\Controller\ControllerQuestion;

echo '<div class="flex flex-col gap-10 mt-10">
        <h1 class="title text-dark text-2xl font-semibold">Organisateur</h1>
        <div class="flex flex-col gap-3">';
foreach ($questionsOrga as $question) {
    ControllerQuestion::afficheVue('question/question.php', ['question' => $question]);
}

if (!$questionsOrga) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Responsable</h1>
      <div class="flex flex-col gap-3">';
foreach ($questionsRepre as $question) {
    ControllerQuestion::afficheVue('question/question.php', ['question' => $question]);
}

if (!$questionsRepre) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">CoAuteur</h1>
      <div class="flex flex-col gap-3">';
foreach ($questionsCoau as $question) {
    ControllerQuestion::afficheVue('question/question.php', ['question' => $question]);
}

if (!$questionsVota) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Votant</h1>
      <div class="flex flex-col gap-3">';
foreach ($questionsVota as $question) {
    ControllerQuestion::afficheVue('question/question.php', ['question' => $question]);
}

if (!$questionsVota) echo '<span class="text-center">Vous n\'avez pas de vote en cours</span>';
echo '</div>
    </div>';
