<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Proposition;

class PropositionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDPROPOSITION',
            'IDQUESTION',
        );
    }
    function getNomTable(): string {
        return "overviewProposition";
    }

    function getNomClePrimaire(): string {
        return "IDPROPOSITION";
    }

    function getProcedureInsert(): string { return ""; }

    function getProcedureUpdate(): string { return ""; }

    public function construire(array $propositionFormatTableau) : Proposition {
        return new Proposition(
            $propositionFormatTableau['IDPROPOSITION'],
            $propositionFormatTableau['IDQUESTION'],
        );
    }

    function ajouterProposition(): int {
        $sql = "SELECT AjouterPropositions FROM DUAL";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute();
        $idProposition = $pdoStatement->fetch();
        return $idProposition;
    }

}