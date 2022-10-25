<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Section;

class SectionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'idTexte',
            'titre',
            'texte',
            'idQuestion',
            );
    }
    function getNomTable(): string {
        return "TextesQuestions";
    }

    function getNomClePrimaire(): string {
        return "idQuestion";
    }

    function getProcedureInsert(): string {
        return "";
    }

    public function construire(array $sectionFormatTableau) : Section {
        return new Section(
            $sectionFormatTableau['idTexte'],
            $sectionFormatTableau['titre'],
            $sectionFormatTableau['texte'],
            $sectionFormatTableau['idQuestion'],
        );
    }

}