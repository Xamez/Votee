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
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);;
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

}