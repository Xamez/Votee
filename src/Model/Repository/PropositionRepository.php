<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Proposition;
use App\Votee\Model\Repository\DatabaseConnection as DatabaseConnection;
use PDOException;

class PropositionRepository extends AbstractRepository {

    function getNomSequence(): string { return "propositions_seq"; }
    function getNomTable(): string { return "overviewProposition"; }
    function getNomClePrimaire(): string { return "IDPROPOSITION"; }

    function getProcedureInsert(): array { return array('procedure' => 'AjouterPropositions', 'VISIBILITEPROPOSITION', 'TITREPROPOSITION'); }
    function getProcedureUpdate(): array { return array('procedure' => 'ModifierPropositions', 'IDPROPOSITION', 'VISIBILITEPROPOSITION', 'IDPROPFUSIONPARENT', 'TITREPROPOSITION'); }
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

    /** Id d'une proposition (visible) d'un login dans une question */
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

    /** Id de la question dans laquelle la proposition donnée est */
    public function getIdQuestion(int $idProposition) {
        $sql = "SELECT idQuestion FROM Recevoir WHERE idProposition = :idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idPropositionTag"=>$idProposition));
        $result = $pdoStatement->fetch();
        return $result[0];
    }

    /** Nombre de fois qu'un utilisateur va pouvoir créer une fusion pour une proposition donnée (généralement 1) */
    public function getFusionRestant(int $idProposition, string $login): ?int {
        $sql = "SELECT nbFusionRestant FROM ScoreFusion WHERE IDPROPOSITION = :idPropositionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idPropositionTag"=>$idProposition, "loginTag" => $login));
        $nbFusionRestant = $pdoStatement->fetch();
        return $nbFusionRestant ? $nbFusionRestant[0] : null;
    }

    /** Les 2 parents d'une proposition fusionnée */
    public function getFilsFusion($idProposition):array {
        $sql = "SELECT idProposition FROM Propositions WHERE idPropFusionParent = :idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("idPropositionTag"=>$idProposition);
        $pdoStatement->execute($value);
        $result = $pdoStatement->fetchAll();
        return $result;
    }

    /** Rajoute 1 point au score pour créer une proposition */
    public function ajouterScoreProposition($login, $idQuestion): bool {
        $sql = "CALL AjouterScorePropositions(:loginTag, :idQuestionTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("idQuestionTag"=>$idQuestion, "loginTag"=>$login));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    /** Enlever 1 point au score pour créer une proposition */
    public function enleverScoreProposition($login, $idQuestion): bool {
        $sql = "CALL EnleverScorePropositions(:loginTag, :idQuestionTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("loginTag" => $login, "idQuestionTag" => $idQuestion));
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}