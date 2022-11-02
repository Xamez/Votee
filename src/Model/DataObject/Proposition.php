<?php

namespace App\Votee\Model\DataObject;

class Proposition extends AbstractDataObject {

    private int $idProposition;
    private int $idQuestion;

    public function __construct(int $idProposition, int $idQuestion) {
        $this->idProposition = $idProposition;
        $this->idQuestion = $idQuestion;
    }

    public function formatTableau(): array {
        return array(
            "IDPROPOSITION" => $this->getIdProposition(),
            "IDQUESTION" => $this->getIdQuestion(),
        );
    }

    public function getIdProposition(): int { return $this->idProposition; }

    public function setIdProposition(int $idProposition): void { $this->idProposition = $idProposition; }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

}