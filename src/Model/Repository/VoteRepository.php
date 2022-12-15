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
        if ($this->getNote($idProposition, $login) == 0) {
            $sql = "CALL AjouterVotes(:loginTag, :idPropositionTag, :noteTag)";
        } else {
            $sql = "CALL ModifierVotes(:loginTag, :idPropositionTag, :noteTag)";
        }
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idPropositionTag" => $idProposition, "loginTag" => $login, "noteTag" => $note);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    function getNote(string $idProposition, string $login) : int {
        $sql = "SELECT note FROM Voter WHERE IDPROPOSITION = :idPropositionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idPropositionTag" => $idProposition, "loginTag" => $login);
        $pdoStatement->execute($values);
        $result = $pdoStatement->fetch();
        if ($result === false) return 0;
        return $result["NOTE"];
    }

    function getNotes(string $idQuestion, string $idProposition) : array {
        $sql = "SELECT login FROM Existe WHERE IDQUESTION = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idQuestionTag" => $idQuestion);
        $pdoStatement->execute($values);
        $result = $pdoStatement->fetchAll();
        $notes = array();
        foreach ($result as $votant) {
            echo $votant["LOGIN"] . "<br>";
            $notes[$votant["LOGIN"]] = $this->getNote($idProposition, $votant["LOGIN"]);
        }
        var_dump($notes);
        echo "<br>";
        return $notes;
    }

    function getGetResultats(string $idQuestion) : array {
        $resultats = array();
        $notes = array();
        $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
        foreach ($propositions as $proposition)
            $notes[$proposition->getIdProposition()] = $this->getNotes($idQuestion, $proposition->getIdProposition());
        foreach ($notes as $idProposition => $note)
            $resultats[$idProposition] = array_count_values($note);
        var_dump($resultats);
        return $resultats;
    }

    function getProcedureInsert(): string { return "AjouterVotes"; }
    function getProcedureUpdate(): string { return "ModifierVotes"; }
    function getProcedureDelete(): string { return ""; }

}