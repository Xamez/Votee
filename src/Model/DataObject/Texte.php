<?php

namespace App\Votee\Model\DataObject;

class Texte extends AbstractDataObject {

    private int $idProposition;
    private int $idSection;
    private string $texte;

    public function __construct(int $idProposition, int $idSection, string $texte) {
        $this->idProposition = $idProposition;
        $this->idSection = $idSection;
        $this->texte = $texte;
    }

    public function formatTableau(): array {
        return array(
            "IDPROPOSITION" => $this->getIdProposition(),
            "IDSECTION" => $this->getIdSection(),
            "TEXTE" => $this->getTexte(),
        );
    }

    public function getIdProposition(): int { return $this->idProposition; }

    public function setIdProposition(int $idProposition): void { $this->idProposition = $idProposition; }

    public function getIdSection(): int { return $this->idSection; }

    public function setIdSection(int $idSection): void { $this->idSection = $idSection; }

    public function getTexte(): string { return $this->texte; }

    public function setTexte(string $texte): void { $this->texte = $texte; }


}