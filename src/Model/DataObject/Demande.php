<?php

namespace App\Votee\Model\DataObject;

class Demande {

    private string $loginDestinataire;
    private string $login;
    private int $idDemande;
    private string $texteDemande;
    private string $roleDemande;
    private string $etatDemande;

    public function __construct(
        string $loginDestinataire,
        string $login,
        int $idDemande,
        string $texteDemande,
        string $roleDemande,
        string $etatDemande)
    {
        $this->loginDestinataire = $loginDestinataire;
        $this->login = $login;
        $this->idDemande = $idDemande;
        $this->texteDemande = $texteDemande;
        $this->roleDemande = $roleDemande;
        $this->etatDemande = $etatDemande;
    }


    public function formatTableau(): array {
        return array(
            "LOGINDESTINATAIRE" => $this->getLoginDestinataire(),
            "LOGIN" => $this->getLogin(),
            "IDDEMANDE" => $this->getIdDemande(),
            "TEXTEDEMANDE" => $this->getTexteDemande(),
            "ROLEDEMANDE" => $this->getRoleDemande(),
            "ETATDEMANDE" => $this->getEtatDemande(),
        );
    }

    public function getLoginDestinataire(): string { return $this->loginDestinataire; }

    public function setLoginDestinataire(string $loginDestinataire): void { $this->loginDestinataire = $loginDestinataire; }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void { $this->login = $login; }

    public function getIdDemande(): int { return $this->idDemande; }

    public function setIdDemande(int $idDemande): void { $this->idDemande = $idDemande; }

    public function getTexteDemande(): string { return $this->texteDemande; }

    public function setTexteDemande(string $texteDemande): void { $this->texteDemande = $texteDemande; }

    public function getRoleDemande(): string { return $this->roleDemande; }

    public function setRoleDemande(string $roleDemande): void { $this->roleDemande = $roleDemande; }

    public function getEtatDemande(): string { return $this->etatDemande; }

    public function setEtatDemande(string $etatDemande): void { $this->etatDemande = $etatDemande; }
}