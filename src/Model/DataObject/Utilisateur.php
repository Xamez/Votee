<?php

namespace App\Votee\Model\DataObject;

use App\Votee\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject {

    private string $login;
    private string $motDePasse;
    private string $nom;
    private string $prenom;

    public function __construct(string $login, string $motDePasse, string $nom, string $prenom) {
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public static function construireDepuisFormulaire (array $tableauFormulaire) : Utilisateur {
        return new Utilisateur($tableauFormulaire['login'],
            MotDePasse::hacher($tableauFormulaire['password']),
            $tableauFormulaire['nom'],
            $tableauFormulaire['prenom'],
        );
    }

    public function formatTableau(): array {
        return array(
            "LOGIN" => $this->getLogin(),
            "MOTDEPASSE" => $this->getMotDePasse(),
            "NOM" => $this->getNom(),
            "PRENOM" => $this->getPrenom(),
        );
    }


    public function getMotDePasse(): string { return $this->motDePasse; }

    public function setMotDePasse(string $mdpHache): void {
        $this->motDePasse = MotDePasse::hacher($mdpHache);
    }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void{ $this->login = $login; }

    public function getNom(): string { return $this->nom; }

    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getPrenom(): string { return $this->prenom; }

    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }

}