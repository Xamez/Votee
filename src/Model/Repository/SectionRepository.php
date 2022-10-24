<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Section;

class SectionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'idTexte',
            'titre',
            'description',
            );
    }
    function getNomTable(): string {
        return "TextesQuestions";
    }

    function getNomClePrimaire(): string {
        return "idTexte";
    }

    function getProcedureInsert(): string {
        return "";
    }

    public function construire(array $questionFormatTableau) : Section {
        return new Section(
            $questionFormatTableau['idTexte'],
            $questionFormatTableau['titre'],
            $questionFormatTableau['description'],
        );
    }

}