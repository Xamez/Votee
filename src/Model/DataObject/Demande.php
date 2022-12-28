<?php

namespace App\Votee\Model\DataObject;

class Demande extends AbstractDataObject {

    private string $loginDestinataire;
    private string $login;
    private ?int $idDemande;
    private string $etatDemande;
    private string $titreDemande;
    private string $texteDemande;
    private ?string $idProposition;
    private ?string $idQuestion;

    public function __construct(
        string $loginDestinataire,
        string $login,
        ?int   $idDemande,
        string $etatDemande,
        string $titreDemande,
        string $texteDemande,
        ?string $idProposition,
        ?string $idQuestion)
    {
        $this->loginDestinataire = $loginDestinataire;
        $this->login = $login;
        $this->idDemande = $idDemande;
        $this->etatDemande = $etatDemande;
        $this->titreDemande = $titreDemande;
        $this->texteDemande = $texteDemande;
        $this->idProposition = $idProposition;
        $this->idQuestion = $idQuestion;
    }


    public function formatTableau(): array {
        return array(
            "LOGINDESTINATAIRE" => $this->getLoginDestinataire(),
            "LOGIN" => $this->getLogin(),
            "IDDEMANDE" => $this->getIdDemande(),
            "ETATDEMANDE" => $this->getEtatDemande(),
            "TITREDEMANDE" => $this->getTitreDemande(),
            "TEXTEDEMANDE" => $this->getTexteDemande(),
            "IDPROPOSITION" => $this->getIdProposition(),
            "IDQUESTION" => $this->getIdQuestion(),
        );
    }

    public function getIdProposition(): ?string { return $this->idProposition; }

    public function setIdProposition(string $idProposition): void { $this->idProposition = $idProposition; }

    public function getIdQuestion(): ?string { return $this->idQuestion; }

    public function setIdQuestion(string $idQuestion): void { $this->idQuestion = $idQuestion; }

    public function getLoginDestinataire(): string { return $this->loginDestinataire; }

    public function setLoginDestinataire(string $loginDestinataire): void { $this->loginDestinataire = $loginDestinataire; }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void { $this->login = $login; }

    public function getIdDemande(): ?int { return $this->idDemande; }

    public function setIdDemande(int $idDemande): void { $this->idDemande = $idDemande; }

    public function getTexteDemande(): string { return $this->texteDemande; }

    public function setTexteDemande(string $texteDemande): void { $this->texteDemande = $texteDemande; }

    public function getTitreDemande(): string { return $this->titreDemande; }

    public function setTitreDemande(string $titreDemande): void { $this->titreDemande = $titreDemande; }

    public function getEtatDemande(): string { return $this->etatDemande; }

    public function setEtatDemande(string $etatDemande): void { $this->etatDemande = $etatDemande; }
}