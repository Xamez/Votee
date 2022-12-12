<?php
echo '<h1 class="title text-dark text-2xl font-semibold">Attente</h1>';
foreach ($demandesAttente as $key=>$demande) {
    echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. $demande->getIdDemande(). '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
        htmlspecialchars($utilisateurs[$key]->getNom()) . ' ' . htmlspecialchars($utilisateurs[$key]->getPrenom()) .
                    '</div>
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
echo '<h1 class="title text-dark text-2xl font-semibold">Acceptée</h1>';
foreach ($demandesAccepte as $key=>$demande) {
    echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. $demande->getIdDemande(). '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
        htmlspecialchars($utilisateurs[$key]->getNom()) . ' ' . htmlspecialchars($utilisateurs[$key]->getPrenom()) .
                    '</div>
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
echo '<h1 class="title text-dark text-2xl font-semibold">Refusée</h1>';
foreach ($demandesRefuse as $key=>$demande) {
    echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. $demande->getIdDemande(). '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
        htmlspecialchars($utilisateurs[$key]->getNom()) . ' ' . htmlspecialchars($utilisateurs[$key]->getPrenom()) .
                    '</div>
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