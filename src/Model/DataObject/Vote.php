<?php

namespace App\Votee\Model\DataObject;

abstract class Vote extends AbstractDataObject
{
    private int $idProposition;
    private string $login;
    private int $note;

    public function __construct(int $idProposition,string $login,int $note)
    {
        $this->idProposition = $idProposition;
        $this->login = $login;
        $this->note = $note;
    }

    public abstract function getVoteDesign($idQuestion, $idProposition): string;

    public function formatTableau(): array{
        return array (
            "IDPROPOSITION" => $this->getIdProposition(),
            "LOGIN" => $this->getLogin(),
            "NOTEPROPOSITION" => $this->getNote(),
        );
    }

    public function getIdProposition(): int { return $this->idProposition; }

    public function setIdProposition(int $idProposition): void { $this->idProposition = $idProposition; }

    public function getLogin(): string { return $this->login; }

    public function setLogin(string $login): void{ $this->login = $login; }

    public function getNote() : int {return $this->note; }

    public function setNote(int $note): void { $this->note = $note; }
}