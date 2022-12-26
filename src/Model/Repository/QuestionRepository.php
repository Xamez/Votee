<?php

namespace App\Votee\Model\Repository;
use App\Votee\Model\DataObject\Question;
use PDOException;

class QuestionRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDQUESTION',
            'VISIBILITE',
            'TITRE',
            'DESCRIPTION',
            'DATEDEBUTQUESTION',
            'DATEFINQUESTION',
            'DATEDEBUTVOTE',
            'DATEFINVOTE',
            'LOGIN',
            'TYPEVOTE'
        );
    }

    function getNomTable(): string { return "Questions"; }
    function getNomClePrimaire(): string { return "IDQUESTION"; }

    function getProcedureInsert(): string { return "AjouterQuestions"; }
    function getProcedureUpdate(): string { return "ModifierQuestions"; }
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
            $questionFormatTableau['LOGIN'],
            $questionFormatTableau['TYPEVOTE']
        );
    }

    public function modifierQuestion(int $idQuestion, string $description, string $visibilite) : bool {
        $sql = "CALL {$this->getProcedureUpdate()}(:idQuestionTag, :visibiliteTag, :descriptionTag)";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = array("idQuestionTag" => $idQuestion, "visibiliteTag" => $visibilite, "descriptionTag" => $description);
        try {
            $pdoStatement->execute($values);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function ajouterQuestion(Question $question):int {
        $this->sauvegarder($question);
        $pdoLastInsert = DatabaseConnection::getPdo()->prepare("SELECT questions_seq.CURRVAL AS lastInsertId FROM DUAL");
        $pdoLastInsert->execute();
        $lastInserId = $pdoLastInsert->fetch();
        return intval($lastInserId[0]);
    }

    public function ajouterVotant(int $idQuestion, string $votant) : bool {
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

    /** Retourne toutes les questions pour lesquelle l'utilisateur donnée est organisateur */
    public function selectQuestionOrga($login): array {
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE LOGIN IN (SELECT LOGIN FROM Organisateurs WHERE login = :paramTag)";
        return self::selectAllCustom($sql, $login);
    }

    /** Retourne toutes les questions pour lesquelle l'utilisateur donnée est responsable */
    public function selectQuestionResp(string $login) {
        $sql = "SELECT DISTINCT q.*
            FROM Questions q JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN RedigerR rr ON p.idProposition = rr.idProposition
            WHERE rr.login = :paramTag";
        return self::selectAllCustom($sql, $login);
    }

    /** Retourne toutes les questions pour lesquelle l'utilisateur donnée est coAuteur */
    public function selectQuestionCoau(string $login) {
        $sql = "SELECT DISTINCT q.*
            FROM Questions q JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN RedigerCA rc ON p.idProposition = rc.idProposition
            WHERE rc.login = :paramTag";
        return self::selectAllCustom($sql, $login);
    }

    /** Retourne toutes les questions pour lesquelle l'utilisateur donnée est votant */
    public function selectQuestionVota(string $login) {
        $sql = "SELECT DISTINCT q.* FROM QUESTIONS q
            JOIN Recevoir r ON q.idQuestion = r.idQuestion
            JOIN Propositions p ON r.idProposition = p.idProposition
            JOIN Voter v ON p.idProposition = v.idProposition
            WHERE v.login = :paramTag";
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

    /** Retourne le nombre de proposition (score) que l'utilisateur donné va pouvoir créer pour la question donnée */
    public function getPropRestant(int $idQuestion, string $login): ?int {
        $sql = "SELECT nbPropRestant FROM ScorePropositions WHERE IDQUESTION = :idQuestionTag AND LOGIN = :loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute(array("idQuestionTag"=>$idQuestion, "loginTag" => $login));
        $nbPropRestant = $pdoStatement->fetch();
        return $nbPropRestant ? $nbPropRestant[0] : null;
    }

    public function selectBySearch($search):array {
        $questions = [];
        $sql = "SELECT * FROM {$this->getNomTable()} WHERE LOWER(TITRE) LIKE '%:searchTag%'";
        $sql = str_replace(":searchTag", strtolower($search), $sql);
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute();
        foreach ($pdoStatement as $formatTableau) $questions[] = $this->construire($formatTableau);
        return $questions;
    }

}