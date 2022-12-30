<?php

use App\Votee\Lib\ConnexionUtilisateur;

$utilisateur = ConnexionUtilisateur::getUtilisateurConnecte();
if ($utilisateur != null) {
    $nom = htmlspecialchars($utilisateur->getNom());
    $prenom = htmlspecialchars($utilisateur->getPrenom());
    if ($small) {
        $nom = substr($nom, 0, 1);
        $prenom = substr($prenom, 0, 1);
        $display = $nom . '. ' . $prenom;
    } else {
        $display = $nom . ' ' . $prenom;
    }
    echo '<a href="./frontController.php?controller=utilisateur&action=compte">'. $display . '</a>
          <a class="flex items-center" href="frontController.php?controller=utilisateur&action=deconnecter">
            <span class="material-symbols-outlined text-dark">logout</span>
          </a>';
} else {
    echo '<a class="flex p-2 text-white bg-main font-semibold rounded-lg" 
             href="./frontController.php?controller=utilisateur&action=connexion">Se connecter</a>';
}


