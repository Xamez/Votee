<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'LOGIN',
            'NOM',
            'PRENOM',
        );
    }

    function getNomTable(): string {
        return "Utilisateurs";
    }

    function getNomClePrimaire(): string {
        return "LOGIN";
    }

    function getProcedureInsert(): string {
        return "";
    }

    function getProcedureUpdate(): string {
        return "";
    }

    function getProcedureDelete(): string { return ""; }

    public function construire(array $utilisateurFormatTableau) : Utilisateur {
        return new Utilisateur(
            $utilisateurFormatTableau['LOGIN'],
            $utilisateurFormatTableau['NOM'],
            $utilisateurFormatTableau['PRENOM'],
        );
    }


    public function selectCoAuteur($idProposition): array {
        $coAuteurs = [];
        $sql = "SELECT u.login, u.nom, u.prenom FROM RedigerCA r
                JOIN Coauteurs c ON r.login = c.login
                JOIN roles ro ON c.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idProposition" => $idProposition);
        $pdoStatement->execute($values);

        foreach ($pdoStatement as $utilisateur) {
            $coAuteurs[] = $this->construire($utilisateur);
        }
        return $coAuteurs;
    }

    public function selectResp($idProposition): ?Utilisateur {
        $sql = "SELECT u.login,  u.nom, u.prenom FROM RedigerR r
                JOIN responsables re ON r.login = re.login
                JOIN roles ro ON re.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idProposition" => $idProposition);
        $pdoStatement->execute($values);

        $responsable = $pdoStatement->fetch();
        return $responsable ? $this->construire($responsable): null;
    }

}