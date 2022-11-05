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
            'IDPROPOSITION',
            'IDSECTION',
            'TEXTE',
        );
    }

    function getProcedureUpdate(): string {
        return "ModifierRecevoir";
    }

    function getProcedureInsert(): string {
        return "AjouterRecevoir";
    }

    public function construire(array $texteFormatTableau) : Texte {
        return new Texte(
            $texteFormatTableau['IDPROPOSITION'],
            $texteFormatTableau['IDSECTION'],
            $texteFormatTableau['TEXTE'],
        );
    }
}