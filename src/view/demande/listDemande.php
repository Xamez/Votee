<?php

use App\Votee\Controller\ControllerDemande;

echo '<div class="flex flex-col gap-10 mt-10">
        <h1 class="title text-dark text-2xl font-semibold">Attente</h1>
        <div class="flex flex-col gap-3">';
foreach ($demandesAttente as $demande) {
    ControllerDemande::afficheVue('demande/demande.php', ['demande' => $demande]);
}
if (!$demandesAttente) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
echo '</div>
    <h1 class="title text-dark text-2xl font-semibold">Acceptée</h1>
    <div class="flex flex-col gap-3">';
foreach ($demandesAccepte as $demande) {
    ControllerDemande::afficheVue('demande/demande.php', ['demande' => $demande]);
}
if (!$demandesAccepte) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Refusée</h1>
      <div class="flex flex-col gap-3">';
foreach ($demandesRefuse as $demande) {
    ControllerDemande::afficheVue('demande/demande.php', ['demande' => $demande]);
}
if (!$demandesRefuse) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
echo '</div>
    </div>';