<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Commentaire;
use PDOException;

class CommentaireRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDCOMMENTAIRE',
            'NUMEROPARAGRAPHE',
            'INDEXCHARDEBUT',
            'INDEXCHARFIN',
            'TEXTECOMMENTAIRE'
        );

    }
    function getNomTable(): string {
        return "Commentaires";
    }

    function getNomClePrimaire(): string {
        return "IDCOMMENTAIRE";
    }

    function getProcedureInsert(): string { return ""; }

    function getProcedureUpdate(): string { return "ModifierCommentaires"; }

    function getProcedureDelete(): string { return "SupprimerCommentaires"; }

    public function construire(array $propositionFormatTableau) : Commentaire {
        return new Commentaire(
            $propositionFormatTableau['IDCOMMENTAIRE'],
            $propositionFormatTableau['NUMEROPARAGRAPHE'],
            $propositionFormatTableau['INDEXCHARDEBUT'],
            $propositionFormatTableau['INDEXCHARFIN'],
            $propositionFormatTableau['TEXTECOMMENTAIRE']
        );
    }

    public function ajouterCommentaireEtStocker($idQuestion, $idProposition, $numeroParagraphe, $indexCharDebut, $indexCharFin, $texteCommentaire): bool {
        // TODO: vérifier si le commentaire avec les mêmes caractéristiques (excepté textCommentaire) existe déjà
        $sql = "CALL AjouterCommentairesEtStocker(:idQuestion, :idProposition, :numeroParagraphe, :indexCharDebut, :indexCharFin, :texteCommentaire)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "idQuestion" => $idQuestion,
            "idProposition" => $idProposition,
            "numeroParagraphe" => $numeroParagraphe,
            "indexCharDebut" => $indexCharDebut,
            "indexCharFin" => $indexCharFin,
            "texteCommentaire" => $texteCommentaire
        );
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function getCommentaireById($idProposition) {
        $sql = "SELECT IDQUESTION, IDPROPOSITION, s.IDCOMMENTAIRE, NUMEROPARAGRAPHE, INDEXCHARDEBUT, INDEXCHARFIN, TEXTECOMMENTAIRE  FROM Stocker s JOIN Commentaires c ON s.idCommentaire = c.idCommentaire WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "idProposition" => $idProposition
        );
        try {
            $pdoStatement->execute($values);
            $result = $pdoStatement->fetchAll();
            $commentaires = array();
            foreach ($result as $row) {
                $commentaires[] = $this->construire($row);
            }
            return $commentaires;
        } catch (PDOException) {
            return null;
        }
    }

}