<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\AbstractDataObject;
use App\Votee\Model\DataObject\Demande;
use PDOException;

class DemandeRepository extends AbstractRepository {

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

    public function getNomTable(): string { return "Effectuer"; }
    public function getNomClePrimaire(): string { return "IDDEMANDE"; }

    function getProcedureInsert(): string { return "AjouterDemandes"; }
    function getProcedureUpdate(): string { return "ModifierDemandes"; }
    function getProcedureDelete(): string { return ""; }


    /** Retourne la liste des toutes les demandes dont l'utilisateur est le destinataire */
    public function getDemandeByDest($login): array {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE LOGINDESTINATAIRE = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login));
        $demandes = [];
        foreach ($pdoStatement as $formatTableau) {
            $demandes[] = $this->construire($formatTableau);
        }
        return $demandes;
    }

    /** Retourne la liste de toutes les demandes personnelle d'un utilisateur donné */
    public function getDemandeByUtil($login): array {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE LOGIN = :loginTag";;
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login));
        $demandes = [];
        foreach ($pdoStatement as $formatTableau) {
            $demandes[] = $this->construire($formatTableau);
        }
        return $demandes;
    }

    public function ajouterDemande(Demande $demande): bool {
        $sql = "CALL {$this->getProcedureInsert()}(:loginDTag, :loginTag, :titreTag, :txtTag, :idPropTag, :idQuestionTag)";
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
        $sql = "CALL {$this->getProcedureUpdate()}(:idDemandeTag, :etatTag, :idPropTag, :idQuestionTag)";
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

    /**
     * Retourne le nombre de demande en attente pour l'utilisateur donnée
     */
    public function selectNbDemande($login): ?int {
        $sql = "SELECT COUNT(*) FROM {$this->getNomTable()} WHERE LOGINDESTINATAIRE = :loginTag AND ETATDEMANDE = 'attente'";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login));
        $nbDemandes = $pdoStatement->fetch();
        return $nbDemandes ? $nbDemandes[0] : null;
    }
}