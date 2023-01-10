<?php

namespace App\Votee\Model\Repository;

use App\Votee\Lib\ConnexionUtilisateur;
use App\Votee\Model\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository {


    function getNomSequence(): string { return ""; }
    function getNomTable(): string { return "Utilisateurs"; }
    function getNomClePrimaire(): string { return "LOGIN"; }

    function getProcedureInsert(): array { return array('procedure' => 'AjouterUtilisateurs', 'LOGIN', 'MOTDEPASSE', 'NOM', 'PRENOM', 'DESCRIPTION'); }
    function getProcedureUpdate(): array { return array('procedure' => 'ModifierUtilisateurs', 'LOGIN', 'MOTDEPASSE', 'NOM', 'PRENOM', 'DESCRIPTION'); }
    function getProcedureDelete(): string { return ""; }

    public function construire(array $utilisateurFormatTableau) : Utilisateur {
        return new Utilisateur(
            $utilisateurFormatTableau['LOGIN'],
            $utilisateurFormatTableau['MOTDEPASSE'],
            $utilisateurFormatTableau['NOM'],
            $utilisateurFormatTableau['PRENOM'],
            $utilisateurFormatTableau['NBQUESTRESTANT'],
            $utilisateurFormatTableau['DESCRIPTION']
        );
    }

    /** Ensemble des roles d'un utilisateur pour une question donné */
    public function getRolesQuestion($login, $idQuestion): array {
        $roles = [];
        $procedures = ["Responsable", "Organisateur", "CoAuteur", "Votant", "Specialiste"];
        if (ConnexionUtilisateur::estAdministrateur()) return ["Responsable", "Organisateur", "CoAuteur", "Specialiste"];
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

    /** Ensemble des roles d'un utilisateur pour une proposition donné */
    public function getRolesProposition($login, $idProposition): array {
        $roles = [];
        $procedures = ["Responsable", "CoAuteur"];
        if (ConnexionUtilisateur::estAdministrateur()) return $procedures;
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

    /** Rajoute 1 point au score (utile s'il y a eu une erreur dans l'insertion d'une question) */
    public function ajouterScoreQuestion($login): void {
        $sql = "UPDATE Utilisateurs SET NBQUESTRESTANT = NBQUESTRESTANT + 1 WHERE login = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login));
    }

    /** Tous les acteurs d'une question (responsable, organisateurs et coAuteurs) */
    public function selectAllActorQuestion($idQuestion): array {
        $utilisateurs = [];
        $sql = "SELECT U.* FROM (
                SELECT DISTINCT LOGIN_ORGANISATEUR FROM (
                    SELECT Q.LOGIN_ORGANISATEUR, Q.IDQUESTION FROM EXISTE E
                    JOIN QUESTIONS Q on E.LOGIN = Q.LOGIN_ORGANISATEUR
            
                    UNION
            
                    SELECT LOGIN, IDQUESTION FROM (
                        SELECT RC.LOGIN, R.IDQUESTION FROM REDIGERCA RC
                        JOIN RECEVOIR R ON RC.IDPROPOSITION = R.IDPROPOSITION
            
                        UNION
            
                        SELECT RR.LOGIN, R.IDQUESTION FROM REDIGERR RR
                        JOIN RECEVOIR R ON RR.IDPROPOSITION = R.IDPROPOSITION
                    )
                ) WHERE IDQUESTION =:idQuestionTag
            ) JOIN UTILISATEURS U ON LOGIN_ORGANISATEUR = U.LOGIN";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag" => $idQuestion));
        foreach ($pdoStatement as $utilisateur) {
            $utilisateurs[] = $this->construire($utilisateur);
        }
        return $utilisateurs;
    }

    /** Tous les logins des administrateurs de la base */
    public static function getAdmins() : array {
        $sql = "SELECT * FROM Administrateurs";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute();
        $admins = [];
        $result = $pdoStatement->fetchAll();
        foreach ($result as $admin) {
            $admins[] = $admin['LOGIN'];
        }
        return $admins;
    }

    /** Tous les administrateurs de la base */
    public function selectAllAdmins() : array {
        $administrateurs = [];
        $sql = "SELECT * FROM UTILISATEURS u JOIN ADMINISTRATEURS a ON u.LOGIN = a.LOGIN";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute();
        foreach ($pdoStatement as $administrateur) {
            $administrateurs[] = $this->construire($administrateur);
        }
        return $administrateurs;
    }

    /** Tous les coAuteurs d'une proposition donnée */
    public function selectCoAuteur($idProposition): array {
        $coAuteurs = [];
        $sql = "SELECT u.* FROM RedigerCA r
                JOIN Coauteurs c ON r.login = c.login
                JOIN roles ro ON c.login = ro.login
                JOIN Utilisateurs u ON ro.login = u.login
                WHERE IDPROPOSITION = :idProposition";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idProposition" => $idProposition));
        foreach ($pdoStatement as $utilisateur) {
            $coAuteurs[] = $this->construire($utilisateur);
        }
        return $coAuteurs;
    }

    /** Tous les responsables d'une proposition donnée */
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

    /** True si l'utilisateur donné est un admin, false sinon */
    public function selectAdministrateur($login): bool {
        $sql = "SELECT * FROM Administrateurs WHERE login = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("loginTag" => $login));
        $isAdmin = $pdoStatement->fetch();
        return (bool)$isAdmin;
    }

    /** Tous les responsables d'une question donnée */
    public function selectRespQuestion($idQuestion): array {
        $responsables = [];
        $sql = "Select DISTINCT U.* FROM Utilisateurs u
                JOIN RedigerR rr ON u.login = rr.login 
                JOIN Recevoir r ON rr.idProposition = r.idProposition 
                WHERE idQuestion = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag" => $idQuestion));
        foreach ($pdoStatement as $responsable) {
            $responsables[] = $this->construire($responsable);
        }
        return $responsables;
    }

    /** Tous les utilisateurs qui peuvent creer une proposition => devenir responsable */
    public function selectProchainResp($idQuestion): array {
        $responsables = [];
        $sql = "SELECT u.* FROM ScorePropositions sp JOIN Utilisateurs u ON u.login = sp.login WHERE nbPropRestant > 0 AND idQuestion = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag" => $idQuestion));
        foreach ($pdoStatement as $responsable) {
            $responsables[] = $this->construire($responsable);
        }
        return $responsables;
    }
}