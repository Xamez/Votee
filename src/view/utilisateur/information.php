<?php

use App\Votee\Lib\ConnexionUtilisateur;

$utilisisateur = ConnexionUtilisateur::getUtilisateurConnecte();
echo $utilisateur->getPrenom();
echo $utilisateur->getNom();