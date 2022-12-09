<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\Question;
use PDOException;

class QuestionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDQUESTION',
            'TYPEVOTE',
            'VISIBILITE',
            'TITRE',
            'DESCRIPTION',
            'DATEDEBUTQUESTION',
            'DATEFINQUESTION',
            'DATEDEBUTVOTE',
            'DATEFINVOTE',
            'LOGIN');
    }

    function getNomTable(): string {
        return "Questions";
    }

    function getNomClePrimaire(): string {
        return "IDQUESTION";
    }

    function getProcedureInsert(): string {
        return "AjouterQuestions";
    }

    function getProcedureUpdate(): string {
        return "ModifierQuestions";
    }

    function getProcedureDelete(): string {
        return "SupprimerQuestions";
    }

    public function construire(array $questionFormatTableau) : Question {
        return new Question(
            $questionFormatTableau['IDQUESTION'],
            $questionFormatTableau['TYPEVOTE'],
            $questionFormatTableau['VISIBILITE'],
            $questionFormatTableau['TITRE'],
            $questionFormatTableau['DESCRIPTION'],
            $questionFormatTableau['DATEDEBUTQUESTION'],
            $questionFormatTableau['DATEFINQUESTION'],
            $questionFormatTableau['DATEDEBUTVOTE'],
            $questionFormatTableau['DATEFINVOTE'],
            $questionFormatTableau['LOGIN']
        );
    }

    public function modifierQuestion(int $idQuestion, string $description, string $visibilite) : bool {
        $sql = "CALL ModifierQuestions(:idQuestionTag, :visibiliteTag, :descriptionTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idQuestionTag" => $idQuestion, "visibiliteTag" => $visibilite, "descriptionTag" => $description);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function ajouterQuestion(Question $question):int {
        $this->sauvegarder($question);
        $pdoLastInsert = DatabaseConnection::getPdo()->prepare("SELECT questions_seq.CURRVAL AS lastInsertId FROM DUAL");
        $pdoLastInsert->execute();
        $lastInserId = $pdoLastInsert->fetch();
        return intval($lastInserId[0]);
    }

    public function ajouterOrganisateur(string $login):bool {
        $sql = "CALL AjouterOrganisateurs(:loginTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array(":loginTag"=>$login);
        try {
            $pdoStatement->execute($value);
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}