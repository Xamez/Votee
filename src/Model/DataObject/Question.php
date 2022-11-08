<?php

namespace App\Votee\Model\DataObject;

class Question extends AbstractDataObject {

    private ?int $idQuestion;
    private string $visibilite;
    private string $titre;
    private string $description;
    private string $dateDebutQuestion;
    private string $dateFinQuestion;
    private string $dateDebutVote;
    private string $dateFinVote;
    private string $login;


    public function __construct(
        ?int   $idQuestion,
        string $visibilite,
        string $titre,
        string $description,
        string $dateDebutQuestion,
        string $dateFinQuestion,
        string $dateDebutVote,
        string $dateFinVote,
        string $login)
    {
        $this->idQuestion = $idQuestion;
        $this->visibilite = $visibilite;
        $this->titre = $titre;
        $this->description = $description;
        $this->dateDebutQuestion = $dateDebutQuestion;
        $this->dateFinQuestion = $dateFinQuestion;
        $this->dateDebutVote = $dateDebutVote;
        $this->dateFinVote = $dateFinVote;
        $this->login = $login;
    }


    public function formatTableau(): array {
        return array(
            "IDQUESTION" => $this->getIdQuestion(),
            "VISIBILITE" => $this->getVisibilite(),
            "TITRE" => $this->getTitre(),
            "DESCRIPTION" => $this->getDescription(),
            "DATEDEBUTQUESTION" => $this->getDateDebutQuestion(),
            "DATEFINQUESTION" => $this->getDateFinQuestion(),
            "DATEDEBUTVOTE" => $this->getDateDebutVote(),
            "DATEFINVOTE" => $this->getDateFinVote(),
            "LOGIN" => $this->getLogin()
        );
    }

    public function getIdQuestion(): ?int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

    public function getVisibilite(): string { return $this->visibilite; }

    public function setVisibilite(string $visibilite): void { $this->visibilite = $visibilite; }

    public function getTitre(): string { return $this->titre; }

    public function setTitre(string $titre): void { $this->titre = $titre; }

    public function getDescription(): string { return $this->description; }

    public function setDescription(string $description): void { $this->description = $description; }

    public function getDateDebutQuestion(): string { return $this->dateDebutQuestion; }

    public function setDateDebutQuestion(string $dateDebutQuestion): void { $this->dateDebutQuestion = $dateDebutQuestion; }

    public function getDateFinQuestion(): string { return $this->dateFinQuestion; }

    public function setDateFinQuestion(string $dateFinQuestion): void { $this->dateFinQuestion = $dateFinQuestion; }

    public function getDateDebutVote(): string { return $this->dateDebutVote; }

    public function setDateDebutVote(string $dateDebutVote): void { $this->dateDebutVote = $dateDebutVote; }

    public function getDateFinVote(): string { return $this->dateFinVote; }

    public function setDateFinVote(string $dateFinVote): void { $this->dateFinVote = $dateFinVote; }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void { $this->login = $login; }

    public function getPeriodeActuelle() : string {
        $date = date('d/m/y');
        if ($date >= $this->getDateDebutQuestion() && $date <= $this->getDateFinQuestion()) {
            return "Période d'écriture";
        } else if ($date >= $this->getDateDebutVote() && $date <= $this->getDateFinVote()) {
            return "Période de vote";
        } else {
            return "Période des résultats";
        }
    }

}