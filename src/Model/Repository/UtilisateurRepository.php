<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Utilisateur;
use PDOException;

class UtilisateurRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'LOGIN',
            'MOTDEPASSE',
            'NOM',
            'PRENOM',
            'NBQUESTRESTANT',
        );
    }

    function getNomTable(): string {
        return "Utilisateurs";
    }

    function getNomClePrimaire(): string {
        return "LOGIN";
    }

    function getProcedureInsert(): string {
        return "AjouterUtilisateurs";
    }

    function getProcedureUpdate(): string {
        return "";
    }

    function getProcedureDelete(): string { return ""; }

    public function construire(array $utilisateurFormatTableau) : Utilisateur {
        return new Utilisateur(
            $utilisateurFormatTableau['LOGIN'],
            $utilisateurFormatTableau['MOTDEPASSE'],
            $utilisateurFormatTableau['NOM'],
            $utilisateurFormatTableau['PRENOM'],
            $utilisateurFormatTableau['NBQUESTRESTANT']
        );
    }

    public function inscrire(Utilisateur $utilisateur): bool {
        $sql = "CALL AjouterUtilisateurs(:loginTag, :mdpTag, :nomTag, :prenomTag)";
        $values = array(
            "loginTag" => $utilisateur->getLogin(),
            "mdpTag" => $utilisateur->getMotDePasse(),
            "nomTag" => $utilisateur->getNom(),
            "prenomTag" => $utilisateur->getPrenom(),
        );
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function getRoleQuestion($login, $idQuestion): ?string {
        $sql = "SELECT GetRoleQuestion(:loginTag, :idQuestionTag) FROM DUAL";
        $values = array("loginTag" => $login, "idQuestionTag" => $idQuestion);
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($values);
        $role = $pdoStatement->fetch();
        return $role[0];
    }

    public function getRoleProposition($login, $idProposition): ?string {
        $sql = "SELECT GetRoleProposition(:loginTag, :idPropositionTag) FROM DUAL";
        $values = array("loginTag" => $login, "idPropositionTag" => $idProposition);
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($values);
        $role = $pdoStatement->fetch();
        return $role[0];
    }

    public function selectCoAuteur($idProposition): array {
        $coAuteurs = [];
        $sql = "SELECT u.* FROM RedigerCA r
                JOIN Coauteurs c ON r.login = c.login
                JOIN roles ro ON c.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("idProposition" => $idProposition);
        $pdoStatement->execute($value);

        foreach ($pdoStatement as $utilisateur) {
            $coAuteurs[] = $this->construire($utilisateur);
        }
        return $coAuteurs;
    }

    public function selectResp($idProposition): ?Utilisateur {
        $sql = "SELECT u.* FROM RedigerR r
                JOIN responsables re ON r.login = re.login
                JOIN roles ro ON re.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("idProposition" => $idProposition);

        $pdoStatement->execute($value);
        $responsable = $pdoStatement->fetch();
        return $responsable ? $this->construire($responsable): null;
    }

    public function selectAdministrateur($login) {
        $sql = "SELECT * FROM Administrateurs WHERE login = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $value = array("loginTag" => $login);
        $pdoStatement->execute($value);
        $isAdmin = $pdoStatement->fetch();
        return (bool)$isAdmin;
    }
}