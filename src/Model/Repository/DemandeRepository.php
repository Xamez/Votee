<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\AbstractDataObject;
use App\Votee\Model\DataObject\Demande;
use PDOException;

class DemandeRepository extends AbstractRepository {

    public function getNomTable(): string { return "Effectuer"; }

    public function getNomClePrimaire(): string { return "IDDEMANDE"; }

    public function construire(array $objetFormatTableau) : AbstractDataObject {
        return new Demande(
            $objetFormatTableau['LOGINDESTINATAIRE'],
            $objetFormatTableau['LOGIN'],
            $objetFormatTableau['IDDEMANDE'],
            $objetFormatTableau['ETATDEMANDE'],
            $objetFormatTableau['TITREDEMANDE'],
            $objetFormatTableau['TEXTEDEMANDE'],
            $objetFormatTableau['IDPROPOSITION'],
            $objetFormatTableau['IDQUESTION'],
        );
    }

    protected function getNomsColonnes(): array {
        return array(
            'LOGINDESTINATAIRE',
            'LOGIN',
            'IDDEMANDE',
            'ETATDEMANDE',
            'TITREDEMANDE',
            'TEXTEDEMANDE',
            'IDPROPOSITION',
            'IDQUESTION',
        );
    }

    function getProcedureInsert(): string { return ""; }

    function getProcedureUpdate(): string { return ""; }

    function getProcedureDelete(): string { return ""; }

    public function getDemandeByDest($login): array {
        $sql = "SELECT * FROM Effectuer WHERE LOGINDESTINATAIRE = :loginTag";
        $value = array("loginTag" => $login);
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($value);
        $demandes = [];
        foreach ($pdoStatement as $formatTableau) {
            $demandes[] = $this->construire($formatTableau);
        }
        return $demandes;
    }

    public function getDemandeByUtil($login): array {
        $sql = "SELECT * FROM Effectuer WHERE LOGIN = :loginTag";
        $value = array("loginTag" => $login);
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($value);
        $demandes = [];
        foreach ($pdoStatement as $formatTableau) {
            $demandes[] = $this->construire($formatTableau);
        }
        return $demandes;
    }

    public function ajouterDemande(Demande $demande): bool {
        $sql = "CALL AjouterDemandes(:loginDTag, :loginTag, :titreTag, :txtTag, :idPropTag, :idQuestionTag)";
        $values = array(
            "loginDTag" => $demande->getLoginDestinataire(),
            "loginTag" => $demande->getLogin(),
            "titreTag" => $demande->getTitreDemande(),
            "txtTag" => $demande->getTexteDemande(),
            "idPropTag" => $demande->getIdProposition(),
            "idQuestionTag" => $demande->getIdQuestion()
        );
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function updateDemande($demande) : bool {
        $sql = "CALL ModifierDemandes(:idDemandeTag, :etatTag, :idPropTag, :idQuestionTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "idDemandeTag" => $demande->getIdDemande(),
            "etatTag" => $demande->getEtatDemande(),
            "idPropTag" => $demande->getIdProposition(),
            "idQuestionTag" => $demande->getIdQuestion(),
        );
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function selectNbDemande($login): ?int {
        $sql = "SELECT COUNT(*) FROM Effectuer WHERE LOGINDESTINATAIRE = :loginTag AND ETATDEMANDE = 'attente'";
        $value = array("loginTag" => $login);
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($value);
        $nbDemandes = $pdoStatement->fetch();
        return $nbDemandes ? $nbDemandes[0] : null;
    }
}