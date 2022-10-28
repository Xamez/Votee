<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Section;

class SectionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDTEXTE',
            'TITRE',
            'TEXTE',
            'IDQUESTION',
            );
    }
    function getNomTable(): string {
        return "SectionTemp";
    }

    function getNomClePrimaire(): string {
        return "IDQUESTION";
    }

    function getProcedureInsert(): string {
        return "";
    }

    public function construire(array $sectionFormatTableau) : Section {
        return new Section(
            $sectionFormatTableau['IDTEXTE'],
            $sectionFormatTableau['TITRE'],
            $sectionFormatTableau['TEXTE'],
            $sectionFormatTableau['IDQUESTION'],
        );
    }

}