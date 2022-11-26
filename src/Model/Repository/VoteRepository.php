<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Vote;

class VoteRepository extends AbstractRepository
{

    function getNomTable(): string {
        return "Voter";
    }

    function getNomClePrimaire(): string {
        return "IDPROPOSITION";
    }

    public function construire(array $voteFormatTableau) : Vote {
        return new Vote(
            $voteFormatTableau['IDPROPOSITION'],
            $voteFormatTableau['LOGIN'],
            $voteFormatTableau['NOTE'],
        );
    }

    protected function getNomsColonnes(): array {
        return array(
            'IDPROPOSITION',
            'LOGIN',
            'NOTE',
        );
    }

    function getProcedureInsert(): string {
        return "";
    }

    function getProcedureUpdate(): string {
        return "";
    }

    function getProcedureDelete(): string { return ""; }

}