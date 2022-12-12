<?php

use App\Votee\Lib\ConnexionUtilisateur;

echo '<div class="flex items-center gap-2">
        <p class="text-main font-semibold">Auteur : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($auteur->getNom()) . ' ' . htmlspecialchars($auteur->getPrenom()) .
       '</div>
        </p>
     </div>
     <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
        <span>' .htmlspecialchars($demande->getTexteDemande()) . '
        </span>
     </div>';

if (ConnexionUtilisateur::estAdministrateur()) {
    echo '<div class="flex justify-center gap-10">
            <a class="w-36 flex p-2 justify-center text-white bg-main font-semibold rounded-lg" 
                href="./frontController.php?controller=demande&action=setDemande&statut=accepte">Accepter
            </a>
            <a class="w-36 flex p-2 justify-center text-white bg-red font-semibold rounded-lg" 
                href="./frontController.php?controller=demande&action=setDemande&statut=refuse">Refuser
            </a>
          </div>';
}