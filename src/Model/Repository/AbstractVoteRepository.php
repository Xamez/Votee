<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Vote;

class AbstractVoteRepository extends AbstractRepository
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

    function ajouterVote(string $idProposition,string $login,int $note) {
        $sql ="CALL AjouterVotes(:loginTag, :idPropositionTag, :noteTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idPropositionTag" => $idProposition, "loginTag" => $login,"noteTag" => $note);
        $pdoStatement->execute($values);
    }


    function getProcedureInsert(): string {
        return "";
    }

    function getProcedureUpdate(): string {
        return "";
    }

    function getProcedureDelete(): string { return ""; }

}