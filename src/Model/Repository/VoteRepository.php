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

    public function voter(Question $question, string $login, string $idProposition, int $note) : bool {
        $voteType = VoteTypes::getFromKey($question->getVoteType());
        $isOk = true;
        if ($voteType == VoteTypes::CLASSEMENT) {
            $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$question->getIdQuestion()));
            foreach ($propositions as $proposition) {
                $sql = "DELETE FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire()} = :idPropositionTag AND LOGIN = :loginTag";
                $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
                $values = array("idPropositionTag" => $proposition->getIdProposition(), "loginTag" => $login);
                try {
                    $pdoStatement->execute($values);
                } catch (PDOException) {
                    $isOk = false;
                }
            }
        }
        return $isOk && $this->ajouterVote($login, $idProposition, $note);
    }

    private function ajouterVote(string $login, string $idProposition, int $note) : bool {
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
            return false;
        }
    }

    public function supprimerVote(string $login, string $idProposition) : bool {
        $sql = "DELETE FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire()} = :idPropositionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idPropositionTag" => $idProposition, "loginTag" => $login);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function getNote(string $idProposition, string $login) : int {
        $sql = "SELECT note FROM {$this->getNomTable()} WHERE IDPROPOSITION = :idPropositionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idPropositionTag" => $idProposition, "loginTag" => $login));
        $result = $pdoStatement->fetch();
        if ($result === false) return 0;
        return $result["NOTE"];
    }

    public function getNotes(Question $question, string $idProposition) : array {
        $idQuestion = $question->getIdQuestion();
        $sql = "SELECT login FROM Existe WHERE IDQUESTION = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag" => $idQuestion));
        $result = $pdoStatement->fetchAll();
        $notes = array();
        $voteType = VoteTypes::getFromKey($question->getVoteType());
        foreach ($result as $votant) {
            $note = $this->getNote($idProposition, $votant["LOGIN"]);
            if ($note == 0) {
                if ($voteType == VoteTypes::CLASSEMENT)
                    $note = -3;
                else if (($voteType == VoteTypes::JUGEMENT_MAJORITAIRE))
                    $note = -3; // par défaut, les personnes n'ayant pas voté sont considérées comme ayant voté contre soit "-2"
            }
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
    public function getResultatsForProposition(Question $question, string $idProposition) : array {
        $notes = $this->getNotes($question, $idProposition);
        $resultats = array_count_values($notes);
        $totalPoints = 0;
        foreach ($resultats as $note => $nombre) {
            $resultats[$note] = round($nombre / sizeof($notes) * 100);
            $totalPoints += $note * $nombre;
        }

        return [$totalPoints, $resultats];
    }

    public function getResultats(Question $question) : array {
        $propositions = (new PropositionRepository())->selectAllByMultiKey(array("idQuestion"=>$_GET['idQuestion']));
        $resultats = array();
        foreach ($propositions as $proposition) {
            if ($proposition->isVisible()) {
                $idProposition = $proposition->getIdProposition();
                $resultats["prop-$idProposition"] = $this->getResultatsForProposition($question, $idProposition);
            }
        }
        if (VoteTypes::getFromKey($question->getVoteType()) == VoteTypes::JUGEMENT_MAJORITAIRE) {
            // on trie les propositions en trouvant la mention majoritaire et si égalité, on trie par ordre décroissant des points:
            // pour la mention majoritaire, on ajoute un par un le nbr de % jusqu'à atteindre 50% puis on prend la dernière mention ajoutée
            $mentions = array();
            foreach ($resultats as $idProposition => $resultat) {
                $mentions[$idProposition] = 0;
                $pourcentages = $resultat[1];
                $total = 0;
                foreach ($pourcentages as $mention => $pourcentage) {
                    $total += $pourcentage;
                    if ($total >= 50) {
                        $mentions[$idProposition] = $mention;
                        break;
                    }
                }
            }
            $points = array();
            foreach ($resultats as $idProposition => $resultat) {
                $points[$idProposition] = $resultat[0];
            }
            // va trier avec les mentions, puis si égalité, avec les points
            array_multisort($mentions, SORT_DESC, $points, SORT_DESC, $resultats);
        } else {
            arsort($resultats); // On trie les résultats par ordre décroissant de points
        }
        // on défini comme key de $resultats 'prop-X' pour que array_multisort puisse trier en gardant les clés intactes
        // une fois fini, on remet les clés correctement avec uniquement l'id de la proposition
        foreach ($resultats as $idProposition => $resultat) {
            $oldIdProposition = $idProposition;
            $idProposition = substr($idProposition, 5);
            $resultats[$idProposition] = $resultat;
            unset($resultats[$oldIdProposition]);
        }
        return $resultats;
    }

    public function getPropositionsGagantes($question, $resultats = null): array {
        if ($resultats == null) $resultats = $this->getResultats($question);
        if (sizeof($resultats) == 0) return array();
        $resultatGagnant = $resultats[array_key_first($resultats)][1];
        $propositionsGagnantes = [];
        foreach ($resultats as $idProposition => $resultat)
            if ($resultat[1] == $resultatGagnant)
                $propositionsGagnantes[] = $idProposition;
        return $propositionsGagnantes;
    }

}