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
    private string $voteType;

    public function __construct(
        ?int   $idQuestion,
        string $visibilite,
        string $titre,
        string $description,
        string $dateDebutQuestion,
        string $dateFinQuestion,
        string $dateDebutVote,
        string $dateFinVote,
        string $login,
        string $voteType)
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
        $this->voteType = $voteType;
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
            "LOGIN" => $this->getLogin(),
            "TYPEVOTE" => $this->getVoteType(),
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

    public function getVoteType(): string {return $this->voteType;}

    public function setvoteType(string $voteType): void{$this->voteType = $voteType;}


    public function getPeriodeActuelle() : string {
        $date = date('Y-m-d');
        if ($date >= $this->changeDate($this->getDateDebutQuestion()) && $date <= $this->changeDate($this->getDateFinQuestion())) {
            return "Période d'écriture";
        } else if ($date >= $this->changeDate($this->getDateDebutVote()) && $date <= $this->changeDate($this->getDateFinVote())) {
            return "Période de vote";
        } else {
            return "Période des résultats";
        }
    }

    public function changeDate(string $date) {
        $old_date = explode('/', $date);
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $date = date_format(date_create($new_data),'Y-m-d');
        return $date;
    }

}