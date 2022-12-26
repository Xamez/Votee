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

    function getNomTable(): string { return "Utilisateurs"; }
    function getNomClePrimaire(): string { return "LOGIN"; }

    function getProcedureInsert(): string { return "AjouterUtilisateur"; }
    function getProcedureUpdate(): string { return ""; }
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
        $sql = "CALL {$this->getProcedureInsert()}(:loginTag, :mdpTag, :nomTag, :prenomTag)";
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

    /** Retourne l'ensemble des roles pour une question et un utilisateur donné */
    public function getRolesQuestion($login, $idQuestion): array {
        $roles = [];
        $procedures = ["Responsable", "Organisateur", "CoAuteur", "Votant"];
        foreach ($procedures as $procedure) {
            $sql = "SELECT :procedureTag(:loginTag, :idQuestionTag) FROM DUAL";
            $sql = str_replace(":procedureTag", 'est' . $procedure, $sql);
            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
            $pdoStatement->execute(array("loginTag" => $login, "idQuestionTag" => $idQuestion));
            $result = $pdoStatement->fetch();
            if ($result[0] == null) continue;
            $roles[] = $procedure;
        }
        return $roles;
    }

    /** Retourne l'ensemble des roles pour une proposition et un utilisateur donné */
    public function getRolesProposition($login, $idProposition): array {
        $roles = [];
        $procedures = ["Responsable", "CoAuteur"];
        foreach ($procedures as $procedure) {
            $sql = "SELECT :procedureTag(:loginTag, :idPropositionTag) FROM DUAL";
            $sql = str_replace(":procedureTag", 'est' . $procedure . 'Prop', $sql);
            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
            $pdoStatement->execute(array("loginTag" => $login, "idPropositionTag" => $idProposition));
            $result = $pdoStatement->fetch();
            if ($result[0] === null) continue;
            $roles[] = $procedure;
        }
        return $roles;
    }

    public function selectCoAuteur($idProposition): array {
        $coAuteurs = [];
        $sql = "SELECT u.* FROM RedigerCA r
                JOIN Coauteurs c ON r.login = c.login
                JOIN roles ro ON c.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);;
        $pdoStatement->execute(array("idProposition" => $idProposition));
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
        $pdoStatement->execute(array("idProposition" => $idProposition));
        $responsable = $pdoStatement->fetch();
        return $responsable ? $this->construire($responsable): null;
    }

    public function selectAdministrateur($login) {
        $sql = "SELECT * FROM Administrateurs WHERE login = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);;
        $pdoStatement->execute(array("loginTag" => $login));
        $isAdmin = $pdoStatement->fetch();
        return (bool)$isAdmin;
    }
}