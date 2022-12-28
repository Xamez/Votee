<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Texte;

class TexteRepository extends AbstractRepository {

    function getNomSequence(): string { return ""; }
    function getNomTable(): string { return "Recevoir"; }
    function getNomClePrimaire(): string { return "IDPROPOSITION"; }

    function getProcedureUpdate(): array { return array('procedure' => 'ModifierRecevoir', 'IDQUESTION', 'IDSECTION', 'IDPROPOSITION', 'TEXTE', 'JAIME'); }
    function getProcedureInsert(): array { return array('procedure' => 'AjouterRecevoir', 'IDQUESTION', 'IDSECTION', 'IDPROPOSITION', 'TEXTE', 'JAIME'); }
    function getProcedureDelete(): string { return ""; }

    public function construire(array $texteFormatTableau) : Texte {
        return new Texte(
            $texteFormatTableau['IDQUESTION'],
            $texteFormatTableau['IDSECTION'],
            $texteFormatTableau['IDPROPOSITION'],
            $texteFormatTableau['TEXTE'],
            $texteFormatTableau['JAIME'],
        );
    }
}