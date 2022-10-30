<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Section;

class SectionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDPROPOSITION',
            'IDSECTION',
            'TITRESECTION',
            'TEXTE',
            'IDQUESTION',
            );
    }
    function getNomTable(): string {
        return "viewSectionTemp";
    }

    function getNomClePrimaire(): string {
        return "IDQUESTION";
    }

    function getProcedureInsert(): string {
        return "AjouterSections";
    }

    public function construire(array $sectionFormatTableau) : Section {
        return new Section(
            $sectionFormatTableau['IDPROPOSITION'],
            $sectionFormatTableau['IDSECTION'],
            $sectionFormatTableau['TITRESECTION'],
            $sectionFormatTableau['TEXTE'],
            $sectionFormatTableau['IDQUESTION'],
        );
    }

}