<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\AbstractDataObject;
use App\Votee\Model\Repository\DatabaseConnection as DatabaseConnection;
use PDOException;

abstract class AbstractRepository {

    public function sauvegarder(AbstractDataObject $object): bool {
        $sql = "CALL " . $this->getProcedureInsert(). "(:" . implode(', :', $this->getNomsColonnes()) . ")";
        $values = $object->formatTableau();
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimer($valeurClePrimaire): void {
        $sql = "DELETE FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire()} = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($value);
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

    public function selectAllByMultiKey(array $valeurAttributs): array {
        $object = [];
        $ligne = "";
        foreach ($valeurAttributs as $key => $valeurAttribut) {
            $ligne .= $key . "= :" . $key . ' AND ';
        }
        $ligne = substr_replace($ligne, "", -5);
        $sql = "SELECT * FROM {$this->getNomTable()}  WHERE $ligne";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($valeurAttributs);

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

    public function selectByMultiKey(array $valeurAttributs) {
        $ligne = "";
        foreach ($valeurAttributs as $key => $valeurAttribut) {
            $ligne .= $key . "= :" . $key . ' AND ';
        }
        $ligne = substr_replace($ligne, "", -5);
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE $ligne";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($valeurAttributs);
        $object = $pdoStatement->fetch();

        return $object ? $this->construire($object) : null;
    }

    protected abstract function getNomTable(): string;

    protected abstract function getNomClePrimaire(): string;

    protected abstract function construire(array $objetFormatTableau);

    protected abstract function getNomsColonnes(): array;

    protected abstract function getProcedureInsert(): string;
}