<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Vote;
use App\Votee\Model\DataObject\VoteTypes;
use PDOException;

class VoteRepository extends AbstractRepository {

    function getNomTable(): string {
        return "Voter";
    }

    function getNomClePrimaire(): string {
        return "IDPROPOSITION";
    }

    public function construire(array $voteFormatTableau) : Vote {
        $idQuestion = (new PropositionRepository())->getIdQuestion($voteFormatTableau["IDPROPOSITION"]);
        $question = (new QuestionRepository())->select($idQuestion);
        $voteType = $question->getVoteType();
        return match ($voteType) {
            VoteTypes::JUGEMENT_MAJORITAIRE => new JugementMajoritaire($voteFormatTableau["IDPROPOSITION"], $voteFormatTableau["LOGIN"], $voteFormatTableau["NOTEPROPOSITION"]),
            VoteTypes::OUI_NON => new OuiNon($voteFormatTableau["IDPROPOSITION"], $voteFormatTableau["LOGIN"], $voteFormatTableau["NOTEPROPOSITION"]),
            default => throw new PDOException("Le type de vote n'est pas reconnu"),
        };
    }

    protected function getNomsColonnes(): array {
        return array(
            'IDPROPOSITION',
            'LOGIN',
            'NOTE',
        );
    }

    public abstract function getVoteDesign($idQuestion, $idVotant, $idProposition): string;

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