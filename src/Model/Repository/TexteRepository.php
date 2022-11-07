<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Texte;

class TexteRepository extends AbstractRepository {

    function getNomTable(): string {
        return "Recevoir";
    }

    function getNomClePrimaire(): string {
        return "IDPROPOSITION";
    }

    protected function getNomsColonnes(): array {
        return array(
            'IDQUESTION',
            'IDSECTION',
            'IDPROPOSITION',
            'TEXTE',
            'JAIME',
        );
    }

    function getProcedureUpdate(): string {
        return "ModifierRecevoir";
    }

    function getProcedureInsert(): string {
        return "AjouterRecevoir";
    }

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