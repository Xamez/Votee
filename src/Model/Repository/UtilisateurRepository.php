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

    public function construire(array $utilisateurFormatTableau) : Utilisateur {
        return new Utilisateur(
            $utilisateurFormatTableau['LOGIN'],
            $utilisateurFormatTableau['NOM'],
            $utilisateurFormatTableau['PRENOM'],
        );
    }
    public function selectCoAuteur($valeurClePrimaire): array {
        $coAuteurs = [];
        $sql = "SELECT u.login, u.nom, u.prenom FROM Rediger r
                JOIN Ecriture e ON r.login = e.login
                JOIN Coauteurs c ON e.login = c.login
                JOIN roles ro ON e.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($values);

        foreach ($pdoStatement as $utilisateur) {
            $coAuteurs[] = $this->construire($utilisateur);
        }
        return $coAuteurs;
    }

    public function selectResp($valeurClePrimaire): ?Utilisateur {
        $sql = "SELECT u.login,  u.nom, u.prenom FROM Rediger r
                JOIN Ecriture e ON r.login = e.login
                JOIN responsables re ON e.login = re.login
                JOIN roles ro ON e.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :valueTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("valueTag" => $valeurClePrimaire);
        $pdoStatement->execute($values);
        $responsable = $pdoStatement->fetch();
        return $responsable ? $this->construire($responsable): null;
    }

}