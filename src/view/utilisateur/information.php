<?php

use App\Votee\Lib\ConnexionUtilisateur;

$utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
?>

<div>
    <div>
        Nom : <?php echo $utilisateur->getNom(); ?>
    </div>
    <div>
        Prenom : <?php echo $utilisateur->getPrenom(); ?>
    </div>
    <div>
        Description : <?php echo $utilisateur->getDescription(); ?>
    </div>
</div>