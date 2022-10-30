<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Section;

class SectionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDSECTION',
            'TITRESECTION',
            'IDQUESTION',
            );
    }
    function getNomTable(): string {
        return "Sections";
    }

    function getNomClePrimaire(): string {
        return "IDQUESTION";
    }

    function getProcedureInsert(): string {
        return "AjouterSections";
    }

    public function construire(array $sectionFormatTableau) : Section {
        return new Section(
            $sectionFormatTableau['IDSECTION'],
            $sectionFormatTableau['TITRESECTION'],
            $sectionFormatTableau['IDQUESTION'],
        );
    }

}