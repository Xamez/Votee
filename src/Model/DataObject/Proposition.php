<?php

namespace App\Votee\Model\DataObject;

class Proposition extends AbstractDataObject {

    private int $idProposition;
    private int $idQuestion;
    private string $visibilite;
    private ?string $idPropFusionParent;


    public function __construct(
        int $idProposition,
        int $idQuestion,
        string $visibilite,
        ?string $idPropFusionParent
    ) {
        $this->idProposition = $idProposition;
        $this->idQuestion = $idQuestion;
        $this->visibilite = $visibilite;
        $this->idPropFusionParent = $idPropFusionParent;
    }

    public function formatTableau(): array {
        return array(
            "IDPROPOSITION" => $this->getIdProposition(),
            "IDQUESTION" => $this->getIdQuestion(),
            "VISIBILITEPROPOSITION" => $this->getVisibilite(),
            "IDPROPFUSIONPARENT" => $this->getIdPropFusionParent()
        );
    }


    public function getIdPropFusionParent(): ?string { return $this->idPropFusionParent; }

    public function setIdPropFusionParent(?string $idPropFusionParent): void { $this->idPropFusionParent = $idPropFusionParent; }

    public function getVisibilite(): string { return $this->visibilite; }

    public function setVisibilite(string $visibilite): void { $this->visibilite = $visibilite; }

    public function getIdProposition(): int { return $this->idProposition; }

    public function setIdProposition(int $idProposition): void { $this->idProposition = $idProposition; }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

}