<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\Question;

class QuestionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'idQuestion',
            'visibilite',
            'systemeVoteQuestion',
            'planTexteQuestion',
            'dateDebutQuestion',
            'dateFinQuestion',
            'dateDebutVote',
            'dateFinVote',
            'idCategorie',
            'login');
    }
    function getNomTable(): string {
        return "Questions";
    }

    function getNomClePrimaire(): string {
        return "idQuestion";
    }

    function getProcedureInsert(): string {
        return "procedure";
    }

    public function construire(array $questionFormatTableau) : Question {
        return new Question(
            $questionFormatTableau['idQuestion'],
            $questionFormatTableau['visibilite'],
            $questionFormatTableau['systemeVoteQuestion'],
            $questionFormatTableau['planTexteQuestion'],
            $questionFormatTableau['dateDebutQuestion'],
            $questionFormatTableau['dateFinQuestion'],
            $questionFormatTableau['dateDebutVote'],
            $questionFormatTableau['dateFinVote'],
            $questionFormatTableau['idCategorie'],
            $questionFormatTableau['login']
        );
    }
}