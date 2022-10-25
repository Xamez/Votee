<?php

namespace App\Votee\Model\DataObject;

class Utilisateur {

    private string $login;
    private string $motDePasse;
    private string $nom;
    private string $prenom;

    public function __construct(string $login, string $motDePasse, string $nom, string $prenom)
    {
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public function formatTableau(): array {
        return array(
            "login" => $this->getLogin(),
            "motDePasse" => $this->getMotDePasse(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
        );
    }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void{ $this->login = $login; }

    public function getMotDePasse(): string { return $this->motDePasse; }

    public function setMotDePasse(string $motDePasse): void { $this->motDePasse = $motDePasse; }

    public function getNom(): string { return $this->nom; }

    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getPrenom(): string { return $this->prenom; }

    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }

}