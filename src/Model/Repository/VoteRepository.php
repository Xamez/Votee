<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Question;
use App\Votee\Model\DataObject\Vote;
use App\Votee\Model\DataObject\VoteTypes;
use PDOException;

class VoteRepository extends AbstractRepository {


    public function construire(array $voteFormatTableau) : Vote {
        $idQuestion = (new PropositionRepository())->getIdQuestion($voteFormatTableau["idProposition"]);
        $question = (new QuestionRepository())->select($idQuestion);
        $voteType = $question->getVoteType();
        return new Vote($voteFormatTableau["idProposition"], $voteFormatTableau["loginVotant"], $voteFormatTableau["noteProposition"], VoteTypes::getFromKey($voteType));
    }

    protected function getNomSequence(): string { return ""; }
    function getNomTable(): string { return "Voter"; }
    function getNomClePrimaire(): string { return "IDPROPOSITION"; }

    function getProcedureInsert(): array { return array('procedure' => 'AjouterVotes'); }
    function getProcedureUpdate(): array { return array('procedure' => "ModifierVotes"); }
    function getProcedureDelete(): string { return ""; }

    function ajouterVote(string $idProposition, string $login, int $note) : bool {
        if ($this->getNote($idProposition, $login) == 0) {
            $sql = "CALL {$this->getProcedureInsert()['procedure']}(:loginTag, :idPropositionTag, :noteTag)";
        } else {
            $sql = "CALL {$this->getProcedureUpdate()['procedure']}(:loginTag, :idPropositionTag, :noteTag)";
        }
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idPropositionTag" => $idProposition, "loginTag" => $login, "noteTag" => $note);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            var_dump($pdoStatement->errorInfo());
            return false;
        }
    }

    function getNote(string $idProposition, string $login) : int {
        $sql = "SELECT note FROM {$this->getNomTable()} WHERE IDPROPOSITION = :idPropositionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idPropositionTag" => $idProposition, "loginTag" => $login));
        $result = $pdoStatement->fetch();
        if ($result === false) return 0;
        return $result["NOTE"];
    }

    function getNotes(Question $question, string $idProposition) : array {
        $idQuestion = $question->getIdQuestion();
        $sql = "SELECT login FROM Existe WHERE IDQUESTION = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag" => $idQuestion));
        $result = $pdoStatement->fetchAll();
        $notes = array();
        foreach ($result as $votant) {
            $note = $this->getNote($idProposition, $votant["LOGIN"]);
            if (VoteTypes::getFromKey($question->getVoteType()) == VoteTypes::JUGEMENT_MAJORITAIRE)
                $notes[$note] = -2; // par défaut, les personnes n'ayant pas voté sont considérées comme ayant voté "à rejeter" soit "-2"
            $notes[$votant["LOGIN"]] = $note;
        }
        return $notes;
    }

    /**
     * @param string $idQuestion ID de la question
     * @param string $idProposition ID de la proposition
     * @return array Tableau de deux élements :
     * - Le premier élément est un entier représentant le nombre de points de la proposition
     * - Le second est un tableau associatif dont les clés sont les notes et les valeurs sont les pourcentages de votants ayant donné cette note
     */
    function getResultatsForProposition(Question $question, string $idProposition) : array {
        $notes = $this->getNotes($question, $idProposition);
        $resultats = array_count_values($notes);
        $totalPoints = 0;
        foreach ($resultats as $note => $nombre) {
            $resultats[$note] = round($nombre / sizeof($notes) * 100);
            $totalPoints += $note * $nombre;
        }

        return [$totalPoints, $resultats];
    }

    function getResultats(Question $question) : array {
        $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
        $resultats = array();
        foreach ($propositions as $proposition) {
            $idProposition = $proposition->getIdProposition();
            $resultats[$idProposition] = $this->getResultatsForProposition($question, $idProposition);
        }
        arsort($resultats); // On trie les résultats par ordre décroissant de points

        return $resultats;
    }

}