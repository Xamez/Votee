<?php

namespace App\Votee\Model\DataObject;

class Groupe extends AbstractDataObject {

    private ?int $idGroupe;
    private string $nomGroupe;

    public function __construct(?int $idGroupe, string $nomGroupe) {
        $this->idGroupe = $idGroupe;
        $this->nomGroupe = $nomGroupe;
    }

    public function formatTableau(): array {
        return array(
            "IDGROUPE" => $this->getIdGroupe(),
            "NOMGROUPE" => $this->getNomGroupe(),
        );
    }

    public function getIdGroupe(): ?int { return $this->idGroupe; }
    public function setIdGroupe(int $idGroupe): void { $this->idGroupe = $idGroupe; }
    public function getNomGroupe(): string { return $this->nomGroupe; }
    public function setNomGroupe(string $nomGroupe): void { $this->nomGroupe = $nomGroupe; }

}