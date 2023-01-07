<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\Question;
use PDOException;

class QuestionRepository extends AbstractRepository {

    function getNomSequence(): string { return "questions_seq"; }

    function getNomTable(): string { return "Questions"; }
    function getNomClePrimaire(): string { return "IDQUESTION"; }

    function getProcedureInsert(): array {
        return array('procedure' => 'AjouterQuestions',
            'VISIBILITE',
            'TITRE',
            'DESCRIPTION',
            'DATEDEBUTQUESTION',
            'DATEFINQUESTION',
            'DATEDEBUTVOTE',
            'DATEFINVOTE',
            'LOGIN_ORGANISATEUR',
            'LOGIN_SPECIALISTE',
            'TYPEVOTE');
    }
    function getProcedureUpdate(): array { return array('procedure' => 'ModifierQuestions', 'IDQUESTION', 'VISIBILITE', 'DESCRIPTION'); }
    function getProcedureDelete(): string { return "SupprimerQuestions"; }

    public function construire(array $questionFormatTableau) : Question {
        return new Question(
            $questionFormatTableau['IDQUESTION'],
            $questionFormatTableau['VISIBILITE'],
            $questionFormatTableau['TITRE'],
            $questionFormatTableau['DESCRIPTION'],
            $questionFormatTableau['DATEDEBUTQUESTION'],
            $questionFormatTableau['DATEFINQUESTION'],
            $questionFormatTableau['DATEDEBUTVOTE'],
            $questionFormatTableau['DATEFINVOTE'],
            $questionFormatTableau['LOGIN_ORGANISATEUR'],
            $questionFormatTableau['LOGIN_SPECIALISTE'],
            $questionFormatTableau['TYPEVOTE']
        );
    }

    /** Toutes les questions pour les quelle l'utilisateur donnée est organisateur */
    public function selectQuestionOrga($login): array {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE login_organisateur = :paramTag";
        return self::selectAllCustom($sql, $login);
    }

    /** Toutes les questions pour les quelle l'utilisateur donnée est responsable */
    public function selectQuestionResp(string $login) {
        $sql = "SELECT DISTINCT q.*
            FROM Questions q JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN RedigerR rr ON p.idProposition = rr.idProposition
            WHERE rr.login = :paramTag";
        return self::selectAllCustom($sql, $login);
    }

    /** Toutes les questions pour les quelle l'utilisateur donnée est spécialiste */
    public function selectQuestionSpecia($login): array {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE login_specialiste = :paramTag";
        return self::selectAllCustom($sql, $login);
    }

    /** Toutes les questions pour les quelle l'utilisateur donnée est coAuteur */
    public function selectQuestionCoau(string $login) {
        $sql = "SELECT DISTINCT q.*
            FROM Questions q JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN RedigerCA rc ON p.idProposition = rc.idProposition
            WHERE rc.login = :paramTag";
        return self::selectAllCustom($sql, $login);
    }

    /** Toutes les questions pour les quelle l'utilisateur donnée est votant */
    public function selectQuestionVota(string $login) {
        $sql = "SELECT DISTINCT q.* FROM QUESTIONS q
            JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN Voter v ON p.idProposition = v.idProposition
            WHERE v.login = :paramTag AND q.visibilite = 'visible'";
        return self::selectAllCustom($sql, $login);
    }

    public function selectAllCustom($sql, $param): array {
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("paramTag"=>$param));
        $questions = $pdoStatement->fetchAll();
        $questionsFormatObjet = array();
        foreach ($questions as $question) {
            $questionsFormatObjet[] = $this->construire($question);
        }
        return $questionsFormatObjet;
    }

    public function ajouterSpecialiste(string $loginSpe) {
        $sql = "CALL AjouterSpecialistes(:loginSpeTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("loginSpeTag" => $loginSpe);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    /** Nombre de propositions (score) que l'utilisateur donné va pouvoir créer pour la question donnée */
    public function getPropRestant(int $idQuestion, string $login): ?int {
        $sql = "SELECT nbPropRestant FROM ScorePropositions WHERE IDQUESTION = :idQuestionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag"=>$idQuestion, "loginTag" => $login));
        $nbPropRestant = $pdoStatement->fetch();
        return $nbPropRestant ? $nbPropRestant[0] : null;
    }

    public function selectVotant($idQuestion): array {
        $votants = [];
        $sql = "SELECT * FROM Utilisateurs u JOIN Existe e ON u.login = e.login WHERE IDQUESTION = :idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag"=>$idQuestion));
        foreach ($pdoStatement as $formatTableau) {
            $votants[] = (new UtilisateurRepository())->construire($formatTableau);
        }
        return $votants;
    }

    public function ajouterVotant($idQuestion, $votant) : bool {
        $sql = "CALL AjouterVotantAQuestion(:idQuestionTag, :votantTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idQuestionTag" => $idQuestion, "votantTag" => $votant);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function supprimerVotant($idQuestion, $votant): bool {
        $sql = "CALL SupprimerVotantDeQuestion(:idQuestionTag, :votantTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idQuestionTag" => $idQuestion, "votantTag" => $votant);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function modifierHeureQuestion($question): bool {
        $sql = "CALL ModifierHeureQuestion(:idQuestionTag, :debutEcritureTag, :finEcritureTag, :debutVoteTag, :finVoteTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array(
            "idQuestionTag" => $question->getIdQuestion(),
            "debutEcritureTag" => date('d/m/Y H:i:s', $question->getDateDebutQuestion()),
            "finEcritureTag" => date('d/m/Y H:i:s', $question->getDateFinQuestion()),
            "debutVoteTag" => date('d/m/Y H:i:s', $question->getDateDebutVote()),
            "finVoteTag" => date('d/m/Y H:i:s', $question->getDateFinVote())
        );
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}