<a href="./frontController.php?action=home"><span class="link-underline link-underline-color">Accueil</span></a>
<a href="./frontController.php?controller=question&action=all"><span class="link-underline link-underline-color">Question</span></a>
<?php

use App\Votee\Model\Repository\DemandeRepository;
use App\Votee\Lib\ConnexionUtilisateur;

if (ConnexionUtilisateur::estConnecte()) {
    echo '<a href="./frontController.php?controller=demande&action=readAllDemande" class="inline-block relative">
                            <span class="link-underline link-underline-color">Demande';
    $utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
    if ($utilisateur != null) {
        $result = (new DemandeRepository())->selectNbDemande($utilisateur->getLogin());
        if ($result != null) {
            if ($result > 0) {
                echo '<span class="bg-main rounded-2xl text-xs text-white w-5 h-5 flex items-center justify-center absolute -top-2 -right-4">' . $result . '</span>';
            }
        }
    }
    echo '</span></a>';
}
if (ConnexionUtilisateur::estAdministrateur()) {
    echo '<a href="./frontController.php?controller=groupe&action=readAllGroupe">
                            <span class="link-underline link-underline-color">Groupes</span>
                          </a>';
}
?>