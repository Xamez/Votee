<?php

namespace App\Votee\Model\DataObject;

class Section extends AbstractDataObject {

    private int $idSection;
    private string $titre;
    private string $description;

    public function __construct(int $idSection, string $titre, string $description) {
        $this->idSection = $idSection;
        $this->titre = $titre;
        $this->description = $description;
    }

    public function formatTableau(): array {
        return array(
            "idQuestion" => $this->getIdSection(),
            "titre" => $this->getTitre(),
            "description" => $this->getDescription(),
        );
    }

    public function getIdSection(): int { return $this->idSection; }

    public function setIdSection(int $idSection): void { $this->idSection = $idSection; }

    public function getTitre(): string { return $this->titre; }

    public function setTitre(string $titre): void { $this->titre = $titre; }

    public function getDescription(): string { return $this->description; }

    public function setDescription(string $description): void { $this->description = $description; }


}