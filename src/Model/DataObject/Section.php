<?php

namespace App\Votee\Model\DataObject;

class Section extends AbstractDataObject {


    private ?int $idSection;
    private string $titreSection;
    private int $idQuestion;

    public function __construct(?int $idSection, string $titreSection, int $idQuestion) {
        $this->idSection = $idSection;
        $this->titreSection = $titreSection;
        $this->idQuestion = $idQuestion;
    }

    public function formatTableau(): array {
        return array(
            "IDSECTION" => $this->getIdSection(),
            "TITRESECTION" => $this->getTitreSection(),
            "IDQUESTION" => $this->getIdQuestion(),
        );
    }

    public function getIdSection(): ?int { return $this->idSection; }

    public function setIdSection(int $idSection): void { $this->idSection = $idSection; }

    public function getTitreSection(): string { return $this->titreSection; }

    public function setTitreSection(string $titreSection): void { $this->titreSection = $titreSection; }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }
}