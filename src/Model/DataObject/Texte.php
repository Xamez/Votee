<?php

namespace App\Votee\Model\DataObject;

class Texte extends AbstractDataObject {

    private int $idQuestion;
    private int $idSection;
    private ?int $idProposition;
    private string $texte;
    private ?int $like;

    public function __construct(int $idQuestion, int $idSection, ?int $idProposition, string $texte, ?int $like) {
        $this->idQuestion = $idQuestion;
        $this->idSection = $idSection;
        $this->idProposition = $idProposition;
        $this->texte = $texte;
        $this->like = $like;
    }

    public function formatTableau(): array {
        return array(
            "IDQUESTION" => $this->getIdQuestion(),
            "IDSECTION" => $this->getIdSection(),
            "IDPROPOSITION" => $this->getIdProposition(),
            "TEXTE" => $this->getTexte(),
            "JAIME" => $this->getLike(),
        );
    }


    public function getLike(): ?int { return $this->like; }

    public function setLike(int $like): void { $this->like = $like; }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

    public function getIdProposition(): ?int { return $this->idProposition; }

    public function setIdProposition(int $idProposition): void { $this->idProposition = $idProposition; }

    public function getIdSection(): int { return $this->idSection; }

    public function setIdSection(int $idSection): void { $this->idSection = $idSection; }

    public function getTexte(): string { return $this->texte; }

    public function setTexte(string $texte): void { $this->texte = $texte; }
}