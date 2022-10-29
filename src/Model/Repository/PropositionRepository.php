<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Proposition;

class PropositionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'LOGIN',
            'IDPROPOSITION',
            'IDQUESTION',
        );
    }
    function getNomTable(): string {
        return "overviewSRPR";
    }

    function getNomClePrimaire(): string {
        return "IDQUESTION";
    }

    function getProcedureInsert(): string {
        return "AjouterSections";
    }

    public function construire(array $propositionFormatTableau) : Proposition {
        return new Proposition(
            $propositionFormatTableau['LOGIN'],
            $propositionFormatTableau['IDPROPOSITION'],
            $propositionFormatTableau['IDQUESTION'],
        );
    }
}