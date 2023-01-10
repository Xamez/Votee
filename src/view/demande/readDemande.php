<?php

use App\Votee\Lib\ConnexionUtilisateur;

echo '<div class="headerProp md:grid items-center flex flex-col gap-5">
         <span class="text-main font-semibold w-24">Destinataire :</span>
         <div class="flex">
             <a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($destinataire->getLogin()) . '">
                 <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span>'
                        . htmlspecialchars($destinataire->getNom()) . ' ' . htmlspecialchars($destinataire->getPrenom()) .
                 '</div>
             </a>
         </div>
         <span class="text-main font-semibold w-24">Auteur :</span>
         <div class="flex">
             <a href="./frontController.php?controller=utilisateur&action=readUtilisateur&login=' . rawurlencode($auteur->getLogin()) . '">
                 <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($auteur->getNom()) . ' ' . htmlspecialchars($auteur->getPrenom()) .
    '</div>
             </a>
         </div>
          <span class="text-main font-semibold w-24">Statut :</span>
          <div class="flex">';
            if ($demande->getEtatDemande() == 'attente') {
                echo '<div class="bg-white flex gap-1 hidden sm:block shadow-md rounded-2xl w-fit p-2">
                          <span>Attente</span>';
            } else if ($demande->getEtatDemande() == 'accepte') {
                echo '<div class="bg-green flex gap-1 hidden sm:block shadow-md rounded-2xl w-fit p-2">
                          <span>Acceptée</span>';
            } else if ($demande->getEtatDemande() == 'refuse') {
                echo '<div class="bg-red flex gap-1 hidden sm:block shadow-md rounded-2xl w-fit p-2">
                          <span>Refusée</span>';
            }
echo '      </div></div>
    </div>';

echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
if ($demande->getTitreDemande() == 'proposition') {
    echo '<span class="font-semibold text-lg">Demande de création d\'une proposition :</span>';
    echo '<a class="w-36 p-2 text-white bg-main font-semibold rounded-lg text-center" href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($demande->getIdQuestion()) .'">Voir la question</a>';
} else if ($demande->getTitreDemande() == 'question') {
    echo '<span class="font-semibold text-lg">Demande de création d\'une question :</span>';
} else if ($demande->getTitreDemande() == 'fusion') {
    echo '<span class="font-semibold text-lg">Demande de création d\'une fusion :</span>
          <a class="w-40 p-2 text-white bg-main font-semibold rounded-lg text-center" href="./frontController.php?controller=proposition&action=readProposition&idQuestion='
            . rawurlencode($demande->getIdQuestion()) .'&idProposition=' . rawurlencode($demande->getIdProposition()) .'">Voir la proposition</a>';
}

echo '<span>' .htmlspecialchars($demande->getTexteDemande()) . '</span>
     </div>';

$rolesPropo = ConnexionUtilisateur::getRolesProposition($demande->getIdProposition());
$rolesQuest = ConnexionUtilisateur::getRolesQuestion($demande->getIdQuestion());
if ($demande->getEtatDemande() == 'attente') {
    if (($demande->getTitreDemande() == 'fusion' && in_array("Responsable", $rolesPropo))
        || ($demande->getTitreDemande() == 'question' && ConnexionUtilisateur::estAdministrateur())
        || ($demande->getTitreDemande() == 'proposition' && in_array("Organisateur", $rolesQuest))) {
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