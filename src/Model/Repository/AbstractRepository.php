<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\Repository\DatabaseConnection as DatabaseConnection;

abstract class AbstractRepository {

    public function selectAll(): array {
        $object = [];
        $sql = "SELECT * FROM {$this->getNomTable()}";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute();

        foreach ($pdoStatement as $FormatTableau) {
            $object[] = $this->construire($FormatTableau);
        }
        return $object;
    }

    public function select($valeurClePrimaire) {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire() } = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($values);
        $object = $pdoStatement->fetch();

        return $object ? $this->construire($object) : null;
    }

    protected abstract function getNomTable(): string;

    protected abstract function getNomClePrimaire(): string;

    protected abstract function construire(array $objetFormatTableau);

    protected abstract function getNomsColonnes(): array;

    protected abstract function getProcedureInsert(): string;
}