<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Section;

class SectionRepository extends AbstractRepository {


    function getNomSequence(): string { return ""; }
    function getNomTable(): string { return "Sections"; }
    function getNomClePrimaire(): string { return "IDQUESTION"; }

    function getProcedureInsert(): array { return array('procedure' => 'AjouterSections', 'TITRESECTION', 'IDQUESTION', 'DESCRIPTIONSECTION'); }
    function getProcedureDelete(): string { return ""; }
    function getProcedureUpdate(): array { return []; }

    public function construire(array $sectionFormatTableau) : Section {
        return new Section(
            $sectionFormatTableau['IDSECTION'],
            $sectionFormatTableau['TITRESECTION'],
            $sectionFormatTableau['IDQUESTION'],
            $sectionFormatTableau['DESCRIPTIONSECTION'],
        );
    }

}