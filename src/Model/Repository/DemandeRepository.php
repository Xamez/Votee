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

    function getNomSequence(): string { return ""; }
    public function getNomTable(): string { return "Effectuer"; }
    public function getNomClePrimaire(): string { return "IDDEMANDE"; }

    function getProcedureInsert(): array { return array('procedure' => 'AjouterDemandes', 'LOGINDESTINATAIRE', 'LOGIN', 'TITREDEMANDE', 'TEXTEDEMANDE', 'IDPROPOSITION', 'IDQUESTION'); }
    function getProcedureUpdate(): array { return array('procedure' => 'ModifierDemandes', 'IDDEMANDE', 'ETATDEMANDE', 'IDPROPOSITION', 'IDQUESTION'); }
    function getProcedureDelete(): string { return ""; }


    /** Liste de toutes les demandes dont l'utilisateur est le destinataire */
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

    /** Liste de toutes les demandes personnelle d'un utilisateur donné */
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

    /** Nombre de demandes en attente pour l'utilisateur donnée */
    public function selectNbDemande($login): ?int {
        $sql = "SELECT COUNT(*) FROM {$this->getNomTable()} WHERE LOGINDESTINATAIRE = :loginTag AND ETATDEMANDE = 'attente'";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login));
        $nbDemandes = $pdoStatement->fetch();
        return $nbDemandes ? $nbDemandes[0] : null;
    }
}