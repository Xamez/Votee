<?php

namespace App\Votee\Model\DataObject;

class Vote extends AbstractDataObject {

    private int $idProposition;
    private string $loginVotant;
    private int $note;

    public function __construct(int $idProposition, string $loginVotant, $note) {
        $this->idProposition = $idProposition;
        $this->loginVotant = $loginVotant;
        $this->note =  $note;
    }

    public function formatTableau() : array {
        return array (
            "IDPROPOSITION" => $this->getIdProposition(),
            "LOGIN" => $this->getLoginVotant(),
            "NOTEPROPOSITION" => $this->getNote(),
        );
    }

    public function getIdProposition() : int { return $this->idProposition; }

    public function setIdProposition(int $idProposition) : void { $this->idProposition = $idProposition; }

    public function getLoginVotant() : string { return $this->loginVotant; }

    public function setLoginVotant(string $loginVotant) : void{ $this->loginVotant = $loginVotant; }

    public function getNote() : int {return $this->note; }

    public function setNote(int $note) : void { $this->note = $note; }
}