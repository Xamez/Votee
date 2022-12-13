<?php
echo '<div class="flex flex-col gap-10 mt-10">
        <h1 class="title text-dark text-2xl font-semibold">Attente</h1>
        <div class="flex flex-col gap-3">';
foreach ($demandesAttente as $demande) {
    echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. rawurlencode($demande[1]->getIdDemande()) . '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
        htmlspecialchars($demande[0]->getNom()) . ' ' . htmlspecialchars($demande[0]->getPrenom()) .
                    '</div>
                     <span>' . htmlspecialchars(ucfirst($demande[1]->getTitreDemande())). '</span>
                 </div>
                 <div class="flex gap-4 items-center">
                     <div class="bg-white flex gap-1 shadow-md rounded-2xl w-fit p-2">
                        <span>Attente</span>
                     </div>
                 <span class="material-symbols-outlined">arrow_forward_ios</span>
                 </div>
             </div>
          </a>';
}
if (!$demandesAttente) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
echo '</div>
    <h1 class="title text-dark text-2xl font-semibold">Acceptée</h1>
    <div class="flex flex-col gap-3">';
foreach ($demandesAccepte as $demande) {
    echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. rawurlencode($demande[1]->getIdDemande()). '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
        htmlspecialchars($demande[0]->getNom()) . ' ' . htmlspecialchars($demande[0]->getPrenom()) .
                    '</div>
                      <span>' . htmlspecialchars(ucfirst($demande[1]->getTitreDemande())). '</span>
                 </div>
                 <div class="flex gap-4 items-center">
                     <div class="bg-green flex gap-1 shadow-md rounded-2xl w-fit p-2">
                        <span>Acceptée</span>
                     </div>
                     <span class="material-symbols-outlined">arrow_forward_ios</span>
                 </div>
             </div>
          </a>';
}
if (!$demandesAccepte) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
echo '</div>
      <h1 class="title text-dark text-2xl font-semibold">Refusée</h1>
      <div class="flex flex-col gap-3">';
foreach ($demandesRefuse as $demande) {
    echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. rawurlencode($demande[1]->getIdDemande()). '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
        htmlspecialchars($demande[0]->getNom()) . ' ' . htmlspecialchars($demande[0]->getPrenom()) .
                    '</div>
                     <span>' . htmlspecialchars(ucfirst($demande[1]->getTitreDemande())). '</span>
                 </div>
                 <div class="flex gap-4 items-center">
                     <div class="bg-red flex gap-1 shadow-md rounded-2xl w-fit p-2">
                        <span>Refusée</span>
                     </div>
                     <span class="material-symbols-outlined">arrow_forward_ios</span>
                 </div>
             </div>
          </a>';
}
if (!$demandesRefuse) echo '<span class="text-center">Vous n\'avez pas de demandes en cours</span>';
echo '</div>
    </div>';