<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Vote;
use App\Votee\Model\DataObject\VoteTypes;
use PDOException;

class VoteRepository {

    function getNomTable(): string {
        return "Voter";
    }

    function getNomClePrimaire(): string {
        return "IDPROPOSITION";
    }

    public function construire(array $voteFormatTableau) : Vote {
        var_dump($voteFormatTableau);
        $idQuestion = (new PropositionRepository())->getIdQuestion($voteFormatTableau["idProposition"]);
        $question = (new QuestionRepository())->select($idQuestion);
        $voteType = $question->getVoteType();
        return new Vote($voteFormatTableau["idProposition"], $voteFormatTableau["loginVotant"], $voteFormatTableau["noteProposition"], VoteTypes::getFromKey($voteType));
    }

    protected function getNomsColonnes() : array {
        return array(
            'IDPROPOSITION',
            'LOGIN',
            'NOTE',
        );
    }

    function ajouterVote(string $idProposition, string $login, int $note) : bool {
        $sql ="CALL AjouterVotes(:loginTag, :idPropositionTag, :noteTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idPropositionTag" => $idProposition, "loginTag" => $login, "noteTag" => $note);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    function getProcedureInsert(): string { return "AjouterVotes"; }
    function getProcedureUpdate(): string { return "ModifierVotes"; }
    function getProcedureDelete(): string { return ""; }

}