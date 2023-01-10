<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Groupe;
use PDOException;

class GroupeRepository extends AbstractRepository {

    function getNomSequence(): string { return "groupes_seq"; }
    function getNomTable(): string { return "Groupes"; }
    function getNomClePrimaire(): string { return "IDGROUPE"; }

    function getProcedureInsert(): array { return array('procedure' => 'AjouterGroupes', 'NOMGROUPE'); }
    function getProcedureUpdate(): array { return array('procedure' => 'ModifierGroupes', 'IDGROUPE', 'NOMGROUPE'); }
    function getProcedureDelete(): string { return "SupprimerGroupes"; }

    public function construire(array $propositionFormatTableau) : Groupe {
        return new Groupe (
            $propositionFormatTableau['IDGROUPE'],
            $propositionFormatTableau['NOMGROUPE'],
        );
    }

    public function ajouterAGroupe($idGroupe, $login) {
        $sql = "CALL AjouterUtilisateurAGroupes(:idGroupeTag, :loginTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);;
        try {
            $pdoStatement->execute(array("idGroupeTag" => $idGroupe, "loginTag" => $login));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimerDeGroupe($idGroupe, $login) {
        $sql = "CALL SupprimerUtilisateurDeGroupe(:idGroupeTag, :loginTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("idGroupeTag" => $idGroupe, "loginTag" => $login));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function selectMembres($idGroupe): array {
        $sql = "SELECT * FROM Utilisateurs u JOIN Appartenir a ON u.login = a.login WHERE IDGROUPE = :idGroupeTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("idGroupeTag" => $idGroupe));
            $membres = $pdoStatement->fetchAll();
            $questionsFormatObjet = array();
            foreach ($membres as $membre) {
                $questionsFormatObjet[] = (new UtilisateurRepository())->construire($membre);
            }
            return $questionsFormatObjet;
        } catch (PDOException) {
            return [];
        }
    }

    public function ajouterGroupeAQuestion($idQuestion, $idGroupe) {
        $sql = "CALL AjouterGroupeAQuestion(:idQuestionTag, :idGroupeTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("idQuestionTag" => $idQuestion, "idGroupeTag" => $idGroupe));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimerGroupeDeQuestion($idQuestion, $idGroupe) {
        $sql = "CALL SupprimerGroupeDeQuestion(:idQuestionTag, :idGroupeTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("idQuestionTag" => $idQuestion, "idGroupeTag" => $idGroupe));
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function selectGroupeQuestion($idQuestion): array {
        $sql = "SELECT * FROM Groupes g JOIN ExisterGroupe a ON g.IDGROUPE = a.IDGROUPE WHERE IDQUESTION = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("idQuestionTag" => $idQuestion));
            $groupes = $pdoStatement->fetchAll();
            $groupesFormatObjet = array();
            foreach ($groupes as $groupe) {
                $groupesFormatObjet[] = $this->construire($groupe);
            }
            return $groupesFormatObjet;
        } catch (PDOException) {
            return [];
        }
    }

    /** Retourne tous les groupes dans lesquel l'utilisateur donnée appartient */
    public function selectGroupeByLogin($login): array {
        $sql = "SELECT * FROM Groupes g JOIN Appartenir a ON g.IDGROUPE = a.IDGROUPE WHERE LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute(array("loginTag" => $login));
            $groupes = $pdoStatement->fetchAll();
            $groupesFormatObjet = array();
            foreach ($groupes as $groupe) {
                $groupesFormatObjet[] = $this->construire($groupe);
            }
            return $groupesFormatObjet;
        } catch (PDOException) {
            return [];
        }
    }

}