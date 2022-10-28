<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\Question;

class QuestionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDQUESTION',
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
        return "procedure";
    }

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
        );
    }
}