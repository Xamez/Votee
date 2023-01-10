<?php

namespace App\Votee\Controller;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Lib\Notification;

class AbstractController {

    public static function afficheVue(string $cheminVue, array $parametres = []): void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../view/$cheminVue"; // Charge la vue
        $error = error_get_last();
        print_r($error);
    }

    public static function fatalError(bool $debug, array $error) {
        self::afficheVue("fatalError.php", ["debug" => $debug, "error" => $error]);
    }

    public static function error(string $errorMessage = "") {
        self::afficheVue("view.php",
            [
                "pagetitle" => "Erreur",
                "cheminVueBody" => "error.php",
                "title" => "Un problème est survenu",
                "subtitle" => $errorMessage
            ]);
    }

    public static function pageIntrouvable(): void {
        http_response_code(404);
        self::afficheVue('view.php',
                        ["pagetitle" => "Page introuvable",
                            "cheminVueBody" => "404.php",
                            "title" => "Page introuvable",
                        ]);
    }

    public static function redirection($url): void {
        header("Location: $url");
        exit();
    }

    /** Si l'utilisateur n'est pas connecté, notification et redirection vers l'url */
    public static function redirectConnexion($url): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            (new Notification())->ajouter("danger", "Vous devez vous connecter.");
            self::redirection($url);
        }
    }

    /** Si l'utilisateur n'est pas administrateur, notification et redirection vers l'url */
    public static function redirectAdmin($url): void {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            (new Notification())->ajouter("danger", "Vous n'avez pas les droits.");
            self::redirection($url);
        }
    }
}