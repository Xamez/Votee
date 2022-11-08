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

    function getProcedureDelete(): string {
        return "SupprimerPropositions";
    }


    public function construire(array $propositionFormatTableau) : Proposition {
        return new Proposition(
            $propositionFormatTableau['IDPROPOSITION'],
            $propositionFormatTableau['IDQUESTION'],
        );
    }

    function ajouterProposition():int {
        $sql = "CALL AjouterPropositions()";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute();
        $pdoLastInsert = DatabaseConnection::getPdo()->prepare("SELECT propositions_seq.CURRVAL AS lastInsertId FROM DUAL");
        $pdoLastInsert->execute();
        $lastInserId = $pdoLastInsert->fetch();
        return intval($lastInserId[0]);
    }

}