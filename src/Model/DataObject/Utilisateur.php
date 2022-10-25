<?php

namespace App\Votee\Model\DataObject;

class Utilisateur extends AbstractDataObject {

    private string $login;
    private string $nom;
    private string $prenom;

    public function __construct(string $login,string $nom, string $prenom) {
        $this->login = $login;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public function formatTableau(): array {
        return array(
            "login" => $this->getLogin(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
        );
    }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void{ $this->login = $login; }

    public function getNom(): string { return $this->nom; }

    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getPrenom(): string { return $this->prenom; }

    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }

}