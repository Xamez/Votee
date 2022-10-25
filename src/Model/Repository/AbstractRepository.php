<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\AbstractDataObject;
use App\Votee\Model\Repository\DatabaseConnection as DatabaseConnection;
use PDOException;

abstract class AbstractRepository {

    public function sauvegarder(AbstractDataObject $object): bool {
        $sql = "INSERT INTO " . $this->getNomTable() . " (". implode(', ', $this->getNomsColonnes())
            . ") VALUES (:" . implode(', :', $this->getNomsColonnes()) . ")";
        $values = $object->formatTableau();
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

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

    public function selectAllByKey($valeurClePrimaire): array {
        $object = [];
        $sql = "SELECT * FROM {$this->getNomTable()}  WHERE {$this->getNomClePrimaire() } = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($values);

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