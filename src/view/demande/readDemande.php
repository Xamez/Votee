<?php

use App\Votee\Lib\ConnexionUtilisateur;

echo '<div class="flex items-center gap-2">
        <p class="text-main font-semibold">Auteur : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($auteur->getNom()) . ' ' . htmlspecialchars($auteur->getPrenom()) .
       '</div>
        </p>
     </div>';
echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
if ($demande->getTitreDemande() == 'proposition') {
    echo '<span class="font-semibold text-lg">Demande de création d\'une proposition :</span>';
    echo '<a class="w-36 p-2 text-white bg-main font-semibold rounded-lg" href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($demande->getIdQuestion()) .'">Voir la question</a>';
} else if ($demande->getTitreDemande() == 'question') {
    echo '<span class="font-semibold text-lg">Demande de création d\'une question :</span>';
} else if ($demande->getTitreDemande() == 'fusion') {
    echo '<span class="font-semibold text-lg">Demande de création d\'une fusion :</span>';
    echo '<a class="w-40 p-2 text-white bg-main font-semibold rounded-lg" href="./frontController.php?controller=proposition&action=readProposition&idQuestion='
            . rawurlencode($demande->getIdQuestion()) .'&idProposition=' . rawurlencode($demande->getIdProposition()) .'">Voir la proposition</a>';
}

echo '<span>' .htmlspecialchars($demande->getTexteDemande()) . '
        </span>
     </div>';

if ($demande->getEtatDemande() == 'attente') {
    if (
        ($demande->getTitreDemande() == 'fusion' && ConnexionUtilisateur::estRepresentant($demande->getIdProposition()))
        || ($demande->getTitreDemande() == 'question' && ConnexionUtilisateur::estAdministrateur())
        || ($demande->getTitreDemande() == 'proposition' && ConnexionUtilisateur::estOrganisateur($demande->getIdQuestion()))) {
        echo '<div class="flex justify-center gap-10">
            <a class="w-36 flex p-2 justify-center text-white bg-green font-semibold rounded-lg" 
                href="./frontController.php?controller=demande&action=setDemande&statut=accepte&idDemande=' . rawurlencode($demande->getIdDemande()) . '">Accepter
            </a>
            <a class="w-36 flex p-2 justify-center text-white bg-red font-semibold rounded-lg" 
                href="./frontController.php?controller=demande&action=setDemande&statut=refuse&idDemande=' . rawurlencode($demande->getIdDemande()) . '">Refuser
            </a>
          </div>';
    }
}