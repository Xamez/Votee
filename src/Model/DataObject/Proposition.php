<?php

namespace App\Votee\Model\DataObject;

class Proposition extends AbstractDataObject {

    private string $login;
    private int $idProposition;
    private int $idQuestion;

    public function __construct(string $login, int $idProposition, int $idQuestion) {
        $this->login = $login;
        $this->idProposition = $idProposition;
        $this->idQuestion = $idQuestion;
    }

    public function formatTableau(): array {
        return array(
            "LOGIN" => $this->getLogin(),
            "IDPROPOSITION" => $this->getIdProposition(),
            "IDQUESTION" => $this->getIdQuestion(),
        );
    }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void { $this->login = $login; }

    public function getIdProposition(): int { return $this->idProposition; }

    public function setIdProposition(int $idProposition): void { $this->idProposition = $idProposition; }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

}