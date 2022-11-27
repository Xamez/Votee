<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\Question;

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

    function getProcedureDelete(): string { return ""; }

    public function construire(array $questionFormatTableau) : Question {
        return new Question(
            $questionFormatTableau['IDQUESTION'],
            $questionFormatTableau['VISIBILITE'],
            $questionFormatTableau['TITRE'],
            $questionFormatTableau['DESCRIPTION'],
            $questionFormatTableau['DATEDEBUTQUESTION'],
            $questionFormatTableau['DATEFINQUESTION'],
            $questionFormatTableau['DATEDEBUTVOTE'],
            $questionFormatTableau['DATEFINVOTE'],
            $questionFormatTableau['LOGIN'],
            $questionFormatTableau['TYPEVOTE']
        );
    }

    function ajouterQuestion(Question $question):int {
        $this->sauvegarder($question);
        $pdoLastInsert = DatabaseConnection::getPdo()->prepare("SELECT questions_seq.CURRVAL AS lastInsertId FROM DUAL");
        $pdoLastInsert->execute();
        $lastInserId = $pdoLastInsert->fetch();
        return intval($lastInserId[0]);
    }
}