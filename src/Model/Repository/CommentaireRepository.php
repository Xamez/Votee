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
        return "Commentaire";
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

}