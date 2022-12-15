<?php
echo '<a href="./frontController.php?controller=demande&action=readDemande&idDemande='. rawurlencode($demande[1]->getIdDemande()). '">
            <div class="flex bg-light justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-dark">Demande de : </p>
                    <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' .
    htmlspecialchars($demande[0]->getPrenom()) . ' ' . htmlspecialchars($demande[0]->getNom()) .
    '                </div>
                     <span>' . htmlspecialchars(ucfirst($demande[1]->getTitreDemande())). '</span>
                 </div>
                 <div class="flex gap-4 items-center">';

if ($demande[1]->getEtatDemande() == 'attente') {
    echo '<div class="bg-white flex gap-1 shadow-md rounded-2xl w-fit p-2">
                        <span>Attente</span>';
} else if ($demande[1]->getEtatDemande() == 'accepte') {
    echo '<div class="bg-green flex gap-1 shadow-md rounded-2xl w-fit p-2">
                        <span>Acceptée</span>';
} else if ($demande[1]->getEtatDemande() == 'refuse') {
    echo '<div class="bg-red flex gap-1 shadow-md rounded-2xl w-fit p-2">
                        <span>Refusée</span>';
}
 echo '            </div>
                       <span class="material-symbols-outlined">arrow_forward_ios</span>
                 </div>
             </div>
          </a>';