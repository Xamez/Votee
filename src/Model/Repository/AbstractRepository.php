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

    public function modifier(AbstractDataObject $object): bool {
        $sql = "CALL " . $this->getProcedureUpdate(). "(:" . implode(', :', $this->getNomsColonnes()) . ")";
        $values = $object->formatTableau();
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimer($valeurClePrimaire): bool {
        $sql = "CALL " . $this->getProcedureDelete(). "(:valueTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("valueTag" => $valeurClePrimaire);
        try {
            $pdoStatement->execute($value);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function selectAll() : array {
        $object = [];
        $sql = "SELECT * FROM {$this->getNomTable()}";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute();
        } catch (PDOException) {
            return $object;
        }
        foreach ($pdoStatement as $formatTableau) $object[] = $this->construire($formatTableau);
        return $object;
    }

    public function selectAllByKey($valeurClePrimaire): array {
        $object = [];
        $sql = "SELECT * FROM {$this->getNomTable()}  WHERE {$this->getNomClePrimaire() } = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($values);
        foreach ($pdoStatement as $formatTableau) {
            $object[] = $this->construire($formatTableau);
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

        foreach ($pdoStatement as $formatTableau) {
            $object[] = $this->construire($formatTableau);
        }
        return $object;
    }

    public function select($valeurClePrimaire) : ?AbstractDataObject {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire() } = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($values);
        $result = $pdoStatement->fetch();

        return $result ? $this->construire($result) : null;
    }

    public function selectByMultiKey(array $valeurAttributs) : ?AbstractDataObject {
        $ligne = "";
        foreach ($valeurAttributs as $key => $valeurAttribut) {
            $ligne .= $key . "= :" . $key . ' AND ';
        }
        $ligne = substr_replace($ligne, "", -5);
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE $ligne";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($valeurAttributs);
        $object = $pdoStatement->fetch();

        return $this->construire($object);
    }

    protected abstract function getNomTable(): string;

    protected abstract function getNomClePrimaire(): string;

    protected abstract function construire(array $objetFormatTableau) : AbstractDataObject;

    protected abstract function getNomsColonnes(): array;

    protected abstract function getProcedureInsert(): string;

    protected abstract function getProcedureUpdate(): string;

    protected abstract function getProcedureDelete(): string;
}