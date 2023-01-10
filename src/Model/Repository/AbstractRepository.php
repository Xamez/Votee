<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\AbstractDataObject;
use App\Votee\Model\Repository\DatabaseConnection as DatabaseConnection;
use PDOException;

abstract class AbstractRepository {

    /** Utilise la méthode {@link sauvegarder} en renvoyant l'id de l'object crée en base de donnée (issue d'une sequence) */
    public function sauvegarderSequence(AbstractDataObject $dataObject) : ?int {
        $this->sauvegarder($dataObject);
        $pdoLastInsert = DatabaseConnection::getPdo()->prepare("SELECT {$this->getNomSequence()}.CURRVAL AS lastInsertId FROM DUAL");
        $pdoLastInsert->execute();
        $lastInserId = $pdoLastInsert->fetch();
        return $lastInserId ? intval($lastInserId[0]) : null;
    }

    public function sauvegarder(AbstractDataObject $object): bool {
        $sql = "CALL {$this->getProcedureInsert()['procedure']}(:" . implode(', :', array_slice($this->getProcedureInsert(), 1)) . ")";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        foreach ($this->getProcedureInsert() as $value) $values[$value] = $value;
        try {
            $pdoStatement->execute(array_intersect_key($object->formatTableau(), $values));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function modifier(AbstractDataObject $object): bool {
        $sql = "CALL {$this->getProcedureUpdate()['procedure']} (:" . implode(', :', array_slice($this->getProcedureUpdate(),1)) . ")";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        foreach ($this->getProcedureUpdate() as $value) $values[$value] = $value;
        try {
            $pdoStatement->execute(array_intersect_key($object->formatTableau(), $values));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimer($valeurClePrimaire): bool {
        $sql = "CALL {$this->getProcedureDelete()} (:valueTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("valueTag" => $valeurClePrimaire);
        try {
            $pdoStatement->execute($value);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function selectAll(): array {
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

    public function select($valeurClePrimaire): ?AbstractDataObject {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire() } = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("valueTag" => $valeurClePrimaire));
        $result = $pdoStatement->fetch();
        return $result ? $this->construire($result) : null;
    }

    /** Ensemble des données après l'application d'un filtre de recherche */
    public function selectBySearch($search, $cle): array {
        $objects = [];
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE LOWER({$cle}) LIKE :rechercheTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("rechercheTag" => "%" .strtolower($search)."%"));
        foreach ($pdoStatement as $formatTableau) $objects[] = $this->construire($formatTableau);
        return $objects;
    }

    protected abstract function getNomTable(): string;
    protected abstract function getNomClePrimaire(): string;
    protected abstract function getNomSequence(): string;
    protected abstract function construire(array $objetFormatTableau) : AbstractDataObject;
    protected abstract function getProcedureInsert(): array;
    protected abstract function getProcedureUpdate(): array;
    protected abstract function getProcedureDelete(): string;
}