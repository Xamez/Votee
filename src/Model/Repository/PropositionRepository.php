<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Proposition;
use App\Votee\Model\Repository\DatabaseConnection as DatabaseConnection;
use PDOException;

class PropositionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDPROPOSITION',
            'IDQUESTION',
            'TITREPROPOSITION',
            'VISIBILITEPROPOSITION',
            'IDPROPFUSIONPARENT',
        );

    }
    function getNomTable(): string { return "overviewProposition"; }
    function getNomClePrimaire(): string { return "IDPROPOSITION"; }

    function getProcedureInsert(): string { return "AjouterPropositions"; }
    function getProcedureUpdate(): string { return "ModifierPropositions"; }
    function getProcedureDelete(): string { return "SupprimerPropositions"; }

    public function construire(array $propositionFormatTableau) : Proposition {
        return new Proposition(
            $propositionFormatTableau['IDPROPOSITION'],
            $propositionFormatTableau['IDQUESTION'],
            $propositionFormatTableau['TITREPROPOSITION'],
            $propositionFormatTableau['VISIBILITEPROPOSITION'],
            $propositionFormatTableau['IDPROPFUSIONPARENT']
        );
    }

    /** Retourne l'id d'une proposition (visible) d'un login dans une question */
    public function selectPropById($idQuestion, $login): array {
        $sql = "SELECT p.IDPROPOSITION
            FROM Questions q JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN RedigerR rr ON p.idProposition = rr.idProposition
            WHERE rr.login = :loginTag AND q.idQuestion= :idQuestionTag AND q.VISIBILITE = 'visible'";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login, "idQuestionTag" => $idQuestion));
        $propositions = [];
        foreach ($pdoStatement as $proposition) $propositions[] = $proposition[0];
        return $propositions;
    }

    public function modifierProposition($idProposition, $visibilite, $idPropFusionParent, $titre): bool {
        $sql = "CALL {$this->getProcedureUpdate()}(:idPropositionTag, :visibiliteTag, :idPropFusionParentTag, :titreProp)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "idPropositionTag" => $idProposition,
            "visibiliteTag" => $visibilite,
            "idPropFusionParentTag" => $idPropFusionParent,
            "titreProp" => $titre
        );
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function ajouterProposition($visibite, $titre):int {
        $sql = "CALL {$this->getProcedureInsert()}(:visibiliteTag, :titreTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);;
        $pdoStatement->execute(array("visibiliteTag" => $visibite, "titreTag" => $titre));

        $pdoLastInsert = DatabaseConnection::getPdo()->prepare("SELECT propositions_seq.CURRVAL AS lastInsertId FROM DUAL");
        $pdoLastInsert->execute();
        $lastInserId = $pdoLastInsert->fetch();
        return intval($lastInserId[0]);
    }

    public function ajouterResponsable($login, $idProposition, $oldIdProposition, $idQuestion, $isFusion):bool {
        $sql = "CALL AjouterRepPropRedigerR(:login, :idProposition, :oldIdProposition, :idQuestion, :isFusion)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "login" => $login,
            "idProposition" => $idProposition,
            "oldIdProposition" => $oldIdProposition,
            "idQuestion" => $idQuestion,
            "isFusion" => $isFusion);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function ajouterCoAuteur(string $login, int $idProposition):bool {
        $sql = "CALL AjouterRedigerCA(:utilisateur, :idProposition)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("utilisateur"=>$login, "idProposition"=>$idProposition));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimerCoAuteur(string $login, int $idProposition):bool {
        $sql = "CALL SupprimerRedigerCA(:utilisateur, :idProposition)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("utilisateur"=>$login, "idProposition"=>$idProposition));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function getIdQuestion(int $idProposition) {
        $sql = "SELECT idQuestion FROM Recevoir WHERE idProposition = :idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idPropositionTag"=>$idProposition));
        $result = $pdoStatement->fetch();
        return $result[0];
    }


    public function getFusionRestant(int $idProposition, string $login): ?int {
        $sql = "SELECT nbFusionRestant FROM ScoreFusion WHERE IDPROPOSITION = :idPropositionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idPropositionTag"=>$idProposition, "loginTag" => $login));
        $nbFusionRestant = $pdoStatement->fetch();
        return $nbFusionRestant ? $nbFusionRestant[0] : null;
    }

    public function getFilsFusion($idProposition):array {
        $sql = "SELECT idProposition FROM Propositions WHERE idPropFusionParent = :idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("idPropositionTag"=>$idProposition);
        $pdoStatement->execute($value);
        $result = $pdoStatement->fetchAll();
        return $result;
    }

}