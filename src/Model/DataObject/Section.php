<?php

namespace App\Votee\Model\DataObject;

class Section extends AbstractDataObject {


    private ?int $idSection;
    private string $titreSection;
    private int $idQuestion;
    private string $descriptionSection;

    public function __construct(?int $idSection, string $titreSection, int $idQuestion, string $descriptionSection) {
        $this->idSection = $idSection;
        $this->titreSection = $titreSection;
        $this->idQuestion = $idQuestion;
        $this->descriptionSection = $descriptionSection;
    }

    public function formatTableau(): array {
        return array(
            "IDSECTION" => $this->getIdSection(),
            "TITRESECTION" => $this->getTitreSection(),
            "IDQUESTION" => $this->getIdQuestion(),
            "DESCRIPTIONSECTION" => $this->getDescriptionSection(),
        );
    }


    public function getDescriptionSection(): string { return $this->descriptionSection; }

    public function setDescriptionSection(string $descriptionSection): void { $this->descriptionSection = $descriptionSection; }

    public function getIdSection(): ?int { return $this->idSection; }

    public function setIdSection(int $idSection): void { $this->idSection = $idSection; }

    public function getTitreSection(): string { return $this->titreSection; }

    public function setTitreSection(string $titreSection): void { $this->titreSection = $titreSection; }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }
}