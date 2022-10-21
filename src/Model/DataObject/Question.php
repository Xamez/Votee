<?php

namespace App\Votee\Model\DataObject;

class Question extends AbstractDataObject {


    private ?int $idQuestion;
    private string $visibilite;
    private string $systemeVoteQuestion;
    private string $planTexteQuestion;
    private string $dateDebutQuestion;
    private string $dateFinQuestion;
    private string $dateDebutVote;
    private string $dateFinVote;
    private int $idCategorie;
    private string $login;


    public function __construct(
        ?int $idQuestion,
        string $visibilite,
        string $systemeVoteQuestion,
        string $planTexteQuestion,
        string $dateDebutQuestion,
        string $dateFinQuestion,
        string $dateDebutVote,
        string $dateFinVote,
        int $idCategorie,
        string $login)
    {
        $this->idQuestion = $idQuestion;
        $this->visibilite = $visibilite;
        $this->systemeVoteQuestion = $systemeVoteQuestion;
        $this->planTexteQuestion = $planTexteQuestion;
        $this->dateDebutQuestion = $dateDebutQuestion;
        $this->dateFinQuestion = $dateFinQuestion;
        $this->dateDebutVote = $dateDebutVote;
        $this->dateFinVote = $dateFinVote;
        $this->idCategorie = $idCategorie;
        $this->login = $login;
    }


    public function formatTableau(): array {
        return array(
            "idQuestion" => $this->getIdQuestion(),
            "visibilite" => $this->getVisibilite(),
            "systemeVoteQuestion" => $this->getSystemeVoteQuestion(),
            "planTexteQuestion" => $this->getPlanTexteQuestion(),
            "dateDebutQuestion" => $this->getDateDebutQuestion(),
            "dateFinQuestion" => $this->getDateFinQuestion(),
            "dateDebutVote" => $this->getDateDebutVote(),
            "dateFinVote" => $this->getDateFinVote(),
            "idCategorie" => $this->getIdCategorie(),
            "login" => $this->getLogin()
        );
    }


    public function getIdQuestion(): ?int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

    public function getVisibilite(): string { return $this->visibilite; }

    public function setVisibilite(string $visibilite): void { $this->visibilite = $visibilite; }

    public function getSystemeVoteQuestion(): string { return $this->systemeVoteQuestion; }

    public function setSystemeVoteQuestion(string $systemeVoteQuestion): void { $this->systemeVoteQuestion = $systemeVoteQuestion; }

    public function getPlanTexteQuestion(): string { return $this->planTexteQuestion; }

    public function setPlanTexteQuestion(string $planTexteQuestion): void { $this->planTexteQuestion = $planTexteQuestion; }

    public function getDateDebutQuestion(): string { return $this->dateDebutQuestion; }

    public function setDateDebutQuestion(string $dateDebutQuestion): void { $this->dateDebutQuestion = $dateDebutQuestion; }

    public function getDateFinQuestion(): string { return $this->dateFinQuestion; }

    public function setDateFinQuestion(string $dateFinQuestion): void { $this->dateFinQuestion = $dateFinQuestion; }

    public function getDateDebutVote(): string { return $this->dateDebutVote; }

    public function setDateDebutVote(string $dateDebutVote): void { $this->dateDebutVote = $dateDebutVote; }

    public function getDateFinVote(): string { return $this->dateFinVote; }

    public function setDateFinVote(string $dateFinVote): void { $this->dateFinVote = $dateFinVote; }

    public function getIdCategorie(): int { return $this->idCategorie; }

    public function setIdCategorie(int $idCategorie): void { $this->idCategorie = $idCategorie; }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void { $this->login = $login; }


}