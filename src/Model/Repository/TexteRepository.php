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

    function getProcedureInsert(): string {
        return "";
    }

    protected function getNomsColonnes(): array {
        return array(
            'IDPROPOSITION',
            'IDSECTION',
            'TEXTE',
        );
    }

    public function construire(array $texteFormatTableau) : Texte {
        return new Texte(
            $texteFormatTableau['IDPROPOSITION'],
            $texteFormatTableau['IDSECTION'],
            $texteFormatTableau['TEXTE'],
        );
    }
}