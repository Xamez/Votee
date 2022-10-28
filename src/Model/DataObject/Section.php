<?php

namespace App\Votee\Model\DataObject;

class Section extends AbstractDataObject {

    private ?int $idTexte;
    private int $idQuestion;
    private string $titre;
    private string $texte;

    public function __construct(?int $idTexte, string $titre, string $texte, int $idQuestion) {
        $this->idTexte = $idTexte;
        $this->idQuestion = $idQuestion;
        $this->titre = $titre;
        $this->texte = $texte;
    }

    public function formatTableau(): array {
        return array(
            "IDTEXTE" => $this->getIdTexte(),
            "IDQUESTION" => $this->getIdQuestion(),
            "TITRE" => $this->getTitre(),
            "TEXTE" => $this->getTexte(),
        );
    }

    public function getIdQuestion(): int { return $this->idQuestion; }

    public function setIdQuestion(int $idQuestion): void { $this->idQuestion = $idQuestion; }

    public function getIdTexte(): int { return $this->idTexte; }

    public function setIdTexte(int $idTexte): void { $this->idTexte = $idTexte; }

    public function getTitre(): string { return $this->titre; }

    public function setTitre(string $titre): void { $this->titre = $titre; }

    public function getTexte(): string { return $this->texte; }

    public function setTexte(string $texte): void { $this->texte = $texte; }


}