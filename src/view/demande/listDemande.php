<?php

use App\Votee\Controller\AbstractController;

$etats = ["Attente" => $demandesAttente, "Acceptée" => $demandesAccepte, "Refusée" => $demandesRefuse];

echo '<div class="flex flex-col gap-10 mt-10">';
foreach ($etats as $key=>$etat) {
    echo '<h1 class="title text-dark text-2xl font-semibold">' . $key . '</h1>
          <div class="flex flex-col gap-3">';
    foreach ($etat as $demande) {
        AbstractController::afficheVue('demande/demande.php', ['demande' => $demande]);
    }
    if (!$etat) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
    echo '</div>';
}
echo '</div>';