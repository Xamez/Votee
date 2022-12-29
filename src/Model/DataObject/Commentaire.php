<?php

namespace App\Votee\Model\DataObject;

class Commentaire extends AbstractDataObject {

    private int $idCommentaire;
    private int $numeroParagraphe;
    private int $indexCharDebut;
    private int $indexCharFin;
    private string $texteCommentaire;

    public function __construct(int $idCommentaire, int $numeroParagraphe, int $indexCharDebut, int $indexCharFin, string $texteCommentaire) {
        $this->idCommentaire = $idCommentaire;
        $this->numeroParagraphe = $numeroParagraphe;
        $this->indexCharDebut = $indexCharDebut;
        $this->indexCharFin = $indexCharFin;
        $this->texteCommentaire = $texteCommentaire;
    }

    public function formatTableau(): array {
        return array(
            'IDCOMMENTAIRE' => $this->getIdCommentaire(),
            'NUMEROPARAGRAPHE' => $this->getNumeroParagraphe(),
            'INDEXCHARDEBUT' => $this->getIndexCharDebut(),
            'INDEXCHARFIN' => $this->getIndexCharFin(),
            'TEXTECOMMENTAIRE' => $this->getTexteCommentaire()
        );
    }

    public function getIdCommentaire(): int {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(int $idCommentaire): void {
        $this->idCommentaire = $idCommentaire;
    }

    public function getNumeroParagraphe(): int {
        return $this->numeroParagraphe;
    }

    public function setNumeroParagraphe(int $numeroParagraphe): void {
        $this->numeroParagraphe = $numeroParagraphe;
    }

    public function getIndexCharDebut(): int {
        return $this->indexCharDebut;
    }

    public function setIndexCharDebut(int $indexCharDebut): void {
        $this->indexCharDebut = $indexCharDebut;
    }

    public function getIndexCharFin(): int {
        return $this->indexCharFin;
    }

    public function setIndexCharFin(int $indexCharFin): void {
        $this->indexCharFin = $indexCharFin;
    }

    public function getTexteCommentaire(): string {
        return $this->texteCommentaire;
    }

    public function setTexteCommentaire(string $texteCommentaire): void {
        $this->texteCommentaire = $texteCommentaire;
    }

}