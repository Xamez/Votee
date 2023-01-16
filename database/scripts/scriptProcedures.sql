-- ###############################################
-- DROP ALL
    DROP PROCEDURE AjouterUtilisateurs;
    DROP PROCEDURE AjouterAdministrateurs;
    DROP FUNCTION AjouterRoles;
    DROP PROCEDURE AjouterOrganisateurs;
    DROP PROCEDURE AjouterVotants;
    DROP PROCEDURE AjouterResponsables;
    DROP PROCEDURE AjouterCoAuteurs;
    DROP PROCEDURE AjouterSpecialistes;
    DROP PROCEDURE AjouterDemandes;
    DROP PROCEDURE AjouterGroupes;
    DROP PROCEDURE AjouterUtilisateurAGroupes;
    DROP PROCEDURE AjouterScorePropositions;
    DROP PROCEDURE EnleverScorePropositions;
    DROP PROCEDURE AjouterScoreFusion;
    DROP PROCEDURE AjouterVotantAQuestion;
    DROP PROCEDURE AjouterGroupeAQuestion;


    DROP PROCEDURE ModifierDemandes;
    DROP PROCEDURE ModifierGroupes;
    DROP PROCEDURE ModifierUtilisateurs;


    DROP PROCEDURE SupprimerDemandes;
    DROP PROCEDURE SupprimerGroupes;
    DROP PROCEDURE SupprimerUtilisateurDeGroupe;
    DROP PROCEDURE SupprimerVotantDeQuestion;
    DROP PROCEDURE SupprimerGroupeDeQuestion;


--     DROP SEQUENCE demandes_seq;
--     DROP SEQUENCE groupes_seq;
--     DROP SEQUENCE questions_seq;
--     DROP SEQUENCE sections_seq;
--     DROP SEQUENCE propositions_seq;
--     DROP SEQUENCE commentaires_seq;


    DROP VIEW overviewProposition;
    DROP TRIGGER question_Insert;
    DROP TRIGGER organisateur_ajoutAutomatique;
    DROP TRIGGER ajoutNbQuestionRestant;


    DROP PROCEDURE AjouterQuestions;
    DROP PROCEDURE AjouterSections;
    DROP PROCEDURE AjouterPropositions;
    DROP PROCEDURE AjouterRedigerCA;
    DROP PROCEDURE AjouterRedigerR;
    DROP PROCEDURE AjouterRepPropRedigerR;
    DROP PROCEDURE AjouterVotes;
    DROP PROCEDURE AjouterRecevoir;
    DROP PROCEDURE AjouterCommentairesEtStocker;


    DROP PROCEDURE ModifierQuestions;
    DROP PROCEDURE ModifierHeureQuestion;
    DROP PROCEDURE ModifierSections;
    DROP PROCEDURE ModifierVotes;
    DROP PROCEDURE ModifierRecevoir;
    DROP PROCEDURE ModifierPropositions;
    DROP PROCEDURE ModifierCommentaires;


    DROP PROCEDURE SupprimerQuestions;
    DROP PROCEDURE SupprimerSections;
    DROP PROCEDURE SupprimerPropositions;
    DROP PROCEDURE SupprimerCommentaires;
    DROP PROCEDURE SupprimerRedigerCA;


    DROP FUNCTION GetPropositionGagnante;
    DROP FUNCTION estOrganisateur;
    DROP FUNCTION estResponsable;
    DROP FUNCTION estCoAuteur;
    DROP FUNCTION estVotant;
    DROP FUNCTION estSpecialiste;
    DROP FUNCTION estResponsableProp;
    DROP FUNCTION estCoAuteurProp;


-- ######################################
-- #									#
-- #				USERS 				#
-- #									#
-- ######################################

-- ###############################################
-- SEQUENCES

    CREATE SEQUENCE demandes_seq START WITH 1;
    CREATE SEQUENCE groupes_seq START WITH 1;



-- ###############################################
-- PROCEDURES AJOUT

		CREATE OR REPLACE PROCEDURE AjouterUtilisateurs(p_login Utilisateurs.login%TYPE, p_mdp Utilisateurs.motDePasse%TYPE, p_nom Utilisateurs.nom%TYPE, p_prenom Utilisateurs.prenom%TYPE, p_description UTILISATEURS.description%TYPE) IS
		    isAlreadyUtilisateur NUMBER;
        BEGIN
            SELECT COUNT(login) INTO isAlreadyUtilisateur FROM UTILISATEURS WHERE LOGIN = p_login;
            IF isAlreadyUtilisateur < 1 THEN
			    INSERT INTO Utilisateurs(login, motDePasse, nom, prenom, description, nbQuestRestant) VALUES (p_login, p_mdp, p_nom, p_prenom, p_description, 0);
			ELSE
                RAISE_APPLICATION_ERROR(-20100, 'Cet Utilisateur existe déjà !');
            END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterAdministrateurs(p_login Administrateurs.login%TYPE) IS
		    isRoles NUMBER;
		    isAlreadyAdministrateur NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isRoles FROM Roles WHERE login = p_login;
			SELECT COUNT(login) INTO isAlreadyAdministrateur FROM Administrateurs WHERE login = p_login;

			IF isAlreadyAdministrateur < 1 THEN
                IF isRoles >= 1 THEN
                    RAISE_APPLICATION_ERROR(-20101, 'Cet Utilisateur possède déjà un Rôle !');
                ELSE
                    INSERT INTO Administrateurs(login) VALUES(p_login);
                END IF;
            ELSE
			    RAISE_APPLICATION_ERROR(-20102, 'Cet Utilisateur est déjà Administrateur !');
            END IF;
		END;


		CREATE OR REPLACE FUNCTION AjouterRoles(p_login Roles.login%TYPE) RETURN NUMBER IS
		    isAdmin NUMBER;
		    isAlreadyRoles NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isAdmin FROM Administrateurs WHERE login = p_login;
			SELECT COUNT(login) INTO isAlreadyRoles FROM Roles WHERE login = p_login;

			IF isAlreadyRoles < 1 THEN
                IF isAdmin >= 1 THEN
                    RAISE_APPLICATION_ERROR(-20103, 'Cet Utilisateur est déjà Administrateur !');
                    RETURN 0;
                ELSE
                    INSERT INTO Roles(login) VALUES(p_login);
                    RETURN 1;
                END IF;
            END IF;
			RETURN 0;
		END;


		CREATE OR REPLACE PROCEDURE AjouterOrganisateurs(p_login Organisateurs.login%TYPE) IS
            isAlreadyIn NUMBER;
            isAlreadyOrganisateur NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isAlreadyIn FROM Roles WHERE login = p_login;
			SELECT COUNT(login) INTO isAlreadyOrganisateur FROM Organisateurs WHERE login = p_login;

			IF isAlreadyOrganisateur < 1 THEN
                IF isAlreadyIn < 1 THEN
                    INSERT INTO Roles(login) VALUES(p_login);
                END IF;
                INSERT INTO Organisateurs(login) VALUES(p_login);
            END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterVotants(p_login Votants.login%TYPE) IS
            isAlreadyIn NUMBER;
            isAlreadyInVotants NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isAlreadyIn FROM Roles WHERE login = p_login;
			SELECT COUNT(login) INTO isAlreadyInVotants FROM VOTANTS WHERE login = p_login;

			IF isAlreadyInVotants < 1 THEN
                IF isAlreadyIn >= 1 THEN
                    INSERT INTO Votants(login) VALUES(p_login);
                ELSIF AjouterRoles(p_login) = 1 THEN
                    INSERT INTO Votants(login) VALUES(p_login);
                END IF;
            END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterResponsables(p_login Responsables.login%TYPE) IS
            isAlreadyIn NUMBER;
            isAlreadyInResponsables NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isAlreadyIn FROM Roles WHERE login = p_login;
            SELECT COUNT(login) INTO isAlreadyInResponsables FROM Responsables WHERE login = p_login;
			IF isAlreadyInResponsables < 1 THEN
                IF isAlreadyIn >= 1 THEN
                    INSERT INTO Responsables(login) VALUES(p_login);
                ELSIF AjouterRoles(p_login) = 1 THEN
                    INSERT INTO Responsables(login) VALUES(p_login);
                END IF;
            END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterCoAuteurs(p_login CoAuteurs.login%TYPE) IS
		    isAlreadyIn NUMBER;
		    isAlreadyCoAuteurs NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isAlreadyIn FROM Roles WHERE login = p_login;
			SELECT COUNT(login) INTO isAlreadyCoAuteurs FROM CoAuteurs WHERE login = p_login;

			IF isAlreadyCoAuteurs < 1 THEN
                IF isAlreadyIn >= 1 THEN
                    INSERT INTO CoAuteurs(login) VALUES(p_login);
                ELSIF AjouterRoles(p_login) = 1 THEN
                    INSERT INTO CoAuteurs(login) VALUES(p_login);
                END IF;
            END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterSpecialistes(p_login SPECIALISTE.login%TYPE) IS
            isAlreadyInRoles NUMBER;
            isAlreadySpecialiste NUMBER;
		BEGIN
            SELECT COUNT(login) INTO isAlreadyInRoles FROM ROLES WHERE login = p_login;
			SELECT COUNT(login) INTO isAlreadySpecialiste FROM SPECIALISTE WHERE login = p_login;

            IF isAlreadySpecialiste < 1 THEN
                IF isAlreadyInRoles >= 1 THEN
                    INSERT INTO SPECIALISTE(login) VALUES(p_login);
                ELSIF AjouterRoles(p_login) = 1 THEN
                    INSERT INTO SPECIALISTE(login) VALUES(p_login);
                END IF;
            END IF;
        END;


		CREATE OR REPLACE PROCEDURE AjouterDemandes(p_loginDestinataire Utilisateurs.login%TYPE, p_login Utilisateurs.login%TYPE, p_titreDemande Effectuer.titreDemande%TYPE, p_texteDemande Effectuer.texteDemande%TYPE, p_idProposition Effectuer.idProposition%TYPE, p_idQuestion Effectuer.idQuestion%TYPE) IS
			DEMANDE NUMBER := demandes_seq.NEXTVAL;
		BEGIN
			INSERT INTO Demandes VALUES(DEMANDE);
			INSERT INTO Effectuer(loginDestinataire, login, IDDEMANDE, ETATDEMANDE, titreDemande, texteDemande, idQuestion, idProposition)
			VALUES(p_loginDestinataire, p_login, DEMANDE, 'attente', p_titreDemande, p_texteDemande, p_idQuestion, p_idProposition);
		END;


		CREATE OR REPLACE PROCEDURE AjouterGroupes(p_nomGroupe Groupes.nomGroupe%TYPE) IS
        BEGIN
            INSERT INTO GROUPES(IDGROUPE, NOMGROUPE) VALUES(groupes_seq.nextval, p_nomGroupe);
        END;


		CREATE OR REPLACE PROCEDURE AjouterUtilisateurAGroupes(p_idGroupe Groupes.idGroupe%TYPE, p_login Utilisateurs.login%TYPE) IS
		    isAlreadyInAppartenir NUMBER;
		BEGIN
		    SELECT COUNT(LOGIN) INTO isAlreadyInAppartenir FROM APPARTENIR WHERE LOGIN = p_login AND IDGROUPE = p_idGroupe;
		    IF isAlreadyInAppartenir < 1 THEN
                INSERT INTO APPARTENIR(LOGIN, IDGROUPE) VALUES (p_login, p_idGroupe);
            END IF;
        END;


		CREATE OR REPLACE PROCEDURE AjouterScorePropositions(p_login Utilisateurs.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) IS
            isInScoreProp NUMBER;
            ScoreProp NUMBER;
        BEGIN
            SELECT COUNT(*) INTO isInScoreProp FROM SCOREPROPOSITIONS WHERE LOGIN = p_login AND SCOREPROPOSITIONS.IDQUESTION = p_idQuestion;
            IF isInScoreProp > 0 THEN
                SELECT NBPROPRESTANT INTO ScoreProp FROM SCOREPROPOSITIONS WHERE LOGIN = p_login AND SCOREPROPOSITIONS.IDQUESTION = p_idQuestion;
                IF ScoreProp > -1 AND ScoreProp < 1 THEN
                    UPDATE SCOREPROPOSITIONS SET NBPROPRESTANT = NBPROPRESTANT + 1 WHERE LOGIN = p_login AND IDQUESTION = p_idQuestion;
                END IF;
            ELSIF p_idQuestion IS NOT NULL THEN
                INSERT INTO SCOREPROPOSITIONS VALUES(p_login, p_idQuestion, 1);
            END IF;
        END;


		CREATE OR REPLACE PROCEDURE EnleverScorePropositions(p_login Utilisateurs.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) IS
		    ScoreProp NUMBER;
        BEGIN
            SELECT NBPROPRESTANT INTO ScoreProp FROM SCOREPROPOSITIONS WHERE LOGIN = p_login AND SCOREPROPOSITIONS.IDQUESTION = p_idQuestion;
            IF ScoreProp > -1 AND ScoreProp < 2 THEN
                UPDATE SCOREPROPOSITIONS SET NBPROPRESTANT = NBPROPRESTANT - 1 WHERE LOGIN = p_login AND IDQUESTION = p_idQuestion;
            END IF;
        END;


        CREATE OR REPLACE PROCEDURE AjouterScoreFusion(p_login Utilisateurs.login%TYPE, p_idProposition Propositions.idProposition%TYPE) IS
            isInScoreFusion NUMBER;
            UserScoreFusion NUMBER;
        BEGIN
            SELECT COUNT(*) INTO isInScoreFusion FROM SCOREFUSION WHERE LOGIN = p_login AND SCOREFUSION.IDPROPOSITION = p_idProposition;
            IF isInScoreFusion > 0 THEN
                SELECT NBFUSIONRESTANT INTO UserScoreFusion FROM SCOREFUSION WHERE LOGIN = p_login AND SCOREFUSION.IDPROPOSITION = p_idProposition;
                IF UserScoreFusion > -1 AND UserScoreFusion < 2 THEN
                    UPDATE SCOREFUSION SET NBFUSIONRESTANT = NBFUSIONRESTANT + 1 WHERE LOGIN = p_login AND IDPROPOSITION = p_idProposition;
                END IF;
            ELSIF p_idProposition IS NOT NULL THEN
                INSERT INTO SCOREFUSION(LOGIN, IDPROPOSITION, NBFUSIONRESTANT) VALUES(p_login, p_idProposition, 1);
            END IF;
        END;


        CREATE OR REPLACE PROCEDURE AjouterVotantAQuestion(p_idQuestion Questions.idQuestion%TYPE, p_login Votants.login%TYPE) IS
            isAlreadyInVotants NUMBER;
            isAlreadyInExiste NUMBER;
        BEGIN
            SELECT COUNT(login) INTO isAlreadyInVotants FROM Votants WHERE login = p_login;
            SELECT COUNT(login) INTO isAlreadyInExiste FROM EXISTE WHERE LOGIN = p_login AND IDQUESTION = p_idQuestion;

            IF isAlreadyInVotants < 1 THEN
                AjouterVotants(p_login);
            ELSIF isAlreadyInExiste < 1 THEN
                INSERT INTO Existe(IDQUESTION, LOGIN) VALUES(p_idQuestion, p_login);
            END IF;
        END;


        CREATE OR REPLACE PROCEDURE AjouterGroupeAQuestion(p_idQuestion Questions.idQuestion%TYPE, p_idGroupe GROUPES.idGroupe%TYPE) IS
            isAlreadyInExisterGroupes NUMBER;
        BEGIN
            SELECT COUNT(IDQUESTION) INTO isAlreadyInExisterGroupes FROM EXISTERGROUPE WHERE IDQUESTION = p_idQuestion AND IDGROUPE = p_idGroupe;

            IF isAlreadyInExisterGroupes < 1 THEN
                INSERT INTO EXISTERGROUPE(IDQUESTION, IDGROUPE) VALUES(p_idQuestion, p_idGroupe);
            END IF;
        END;



-- ###############################################
-- PROCEDURES MODIFIER

		CREATE OR REPLACE PROCEDURE ModifierDemandes(p_idDemande Effectuer.idDemande%TYPE, p_etatDemande Effectuer.etatDemande%TYPE, p_idProposition Effectuer.idProposition%TYPE, p_idQuestion Effectuer.idQuestion%TYPE) IS
		BEGIN
			UPDATE Effectuer SET etatDemande = p_etatDemande, idProposition = p_idProposition, idQuestion = p_idQuestion WHERE idDemande = p_idDemande;
		END;


		CREATE OR REPLACE PROCEDURE ModifierGroupes(p_idGroupe Groupes.IDGROUPE%TYPE, p_nomGroupe Groupes.nomGroupe%TYPE) IS
        BEGIN
            UPDATE GROUPES SET NOMGROUPE = p_nomGroupe WHERE IDGROUPE = p_idGroupe;
        END;


		CREATE OR REPLACE PROCEDURE ModifierUtilisateurs(p_login Utilisateurs.login%TYPE, p_mdp Utilisateurs.motDePasse%TYPE, p_nom Utilisateurs.nom%TYPE, p_prenom Utilisateurs.prenom%TYPE, p_description UTILISATEURS.description%TYPE) IS
        BEGIN
            UPDATE UTILISATEURS SET motDePasse = p_mdp, nom = p_nom, prenom = p_prenom, description = p_description WHERE login = p_login;
        END;



-- ###############################################
-- PROCEDURES SUPPRESSION

		CREATE OR REPLACE PROCEDURE SupprimerDemandes(p_idDemande Effectuer.idDemande%TYPE) IS
		BEGIN
			DELETE FROM Effectuer WHERE idDemande = p_idDemande;
			DELETE FROM Demandes WHERE idDemande = p_idDemande;
		END;


		CREATE OR REPLACE PROCEDURE SupprimerGroupes(p_idGroupe Groupes.IDGROUPE%TYPE) IS
        BEGIN
            DELETE FROM EXISTERGROUPE WHERE IDGROUPE = p_idGroupe;
            DELETE FROM APPARTENIR WHERE IDGROUPE = p_idGroupe;
            DELETE FROM GROUPES WHERE IDGROUPE = p_idGroupe;
        END;


		CREATE OR REPLACE PROCEDURE SupprimerUtilisateurDeGroupe(p_idGroupe Groupes.IDGROUPE%TYPE, p_login Utilisateurs.login%TYPE) IS
        BEGIN
            DELETE FROM APPARTENIR WHERE IDGROUPE = p_idGroupe AND LOGIN = p_login;
        END;


		CREATE OR REPLACE PROCEDURE SupprimerVotantDeQuestion(p_idQuestion QUESTIONS.idQuestion%TYPE, p_login UTILISATEURS.login%TYPE) IS
        BEGIN
            DELETE FROM EXISTE WHERE IDQUESTION = p_idQuestion AND LOGIN = p_login;
        END;


		CREATE OR REPLACE PROCEDURE SupprimerGroupeDeQuestion(p_idQuestion Questions.idQuestion%TYPE, p_idGroupe GROUPES.idGroupe%TYPE) IS
        BEGIN
            DELETE FROM EXISTERGROUPE WHERE IDGROUPE = p_idGroupe AND IDQUESTION = p_idQuestion;
        END;



-- ###############################################
-- TRIGGERS, VUES

        -- AJOUT +1 SCORE QUAND ON EFFECTUE UNE DEMANDE
        CREATE OR REPLACE TRIGGER ajoutNbQuestionRestant
        AFTER UPDATE OF ETATDEMANDE OR UPDATE OF IDQUESTION OR UPDATE OF IDPROPOSITION
        ON EFFECTUER
        FOR EACH ROW
        DECLARE
            ScoreQuestion NUMBER;
        BEGIN
            IF :NEW.ETATDEMANDE = 'accepte' THEN
                IF :NEW.TITREDEMANDE = 'fusion' THEN
                    AjouterScoreFusion(:NEW.LOGIN, :NEW.IDPROPOSITION);
                ELSIF :NEW.TITREDEMANDE = 'question' THEN
                    SELECT NBQUESTRESTANT INTO ScoreQuestion FROM UTILISATEURS WHERE LOGIN = :NEW.LOGIN;
                    IF ScoreQuestion > -1 AND ScoreQuestion < 2 THEN
                        UPDATE UTILISATEURS SET UTILISATEURS.NBQUESTRESTANT = UTILISATEURS.NBQUESTRESTANT + 1 WHERE LOGIN = :NEW.LOGIN;
                    END IF;
                ELSIF :NEW.TITREDEMANDE = 'proposition' THEN
                    AjouterScorePropositions(:NEW.LOGIN, :NEW.IDQUESTION);
                END IF;
            END IF;
        END;



-- ######################################
-- #									#
-- #			QUESTIONS 				#
-- #									#
-- ######################################

	-- ###############################################
	-- SEQUENCES, TRIGGERS, VUES

	-- AUTO INCREMENT QUESTIONS
	CREATE SEQUENCE questions_seq START WITH 1;

	-- AUTO INCREMENT SECTIONS
	CREATE SEQUENCE sections_seq START WITH 1;

	-- AUTO INCREMENT Propositions
	CREATE SEQUENCE propositions_seq START WITH 1;

    -- AUTO INCREMENT Commentaires
    CREATE SEQUENCE commentaires_seq START WITH 1;

    -- VUE UTILE PROPOSITIONS SITE
	CREATE OR REPLACE VIEW overviewProposition AS
    SELECT DISTINCT q.IDQUESTION, p.IDPROPOSITION, p.visibiliteProposition, p.idPropFusionParent, p.TITREPROPOSITION FROM Propositions p
        JOIN Recevoir r ON p.idProposition=r.idProposition
        JOIN Questions q ON r.idQuestion = q.idQuestion;


    -- TRIGGER INSERTION QUESTIONS
	CREATE OR REPLACE TRIGGER question_Insert
	BEFORE INSERT ON Questions
	FOR EACH ROW
	DECLARE
		isTitre NUMBER;
		isDesc NUMBER;
	    questActuel NUMBER;
	    ScoreQuestion NUMBER;
	BEGIN
		SELECT COUNT(*) INTO isTitre FROM Questions WHERE titre = :NEW.titre;
		SELECT COUNT(*) INTO isDesc FROM Questions WHERE description = :NEW.description;
		SELECT NBQUESTRESTANT INTO questActuel FROM UTILISATEURS WHERE LOGIN = :NEW.LOGIN_ORGANISATEUR;

		IF ((:NEW.dateDebutQuestion > :NEW.dateFinQuestion) OR (:NEW.dateFinQuestion < :NEW.dateDebutQuestion) OR (:NEW.dateDebutVote > :NEW.dateFinVote) OR (:NEW.dateFinVote < :NEW.dateDebutVote)
			OR
			(:NEW.dateDebutQuestion > :NEW.dateDebutVote) OR (:NEW.dateDebutQuestion > :NEW.dateFinVote) OR (:NEW.dateFinQuestion > :NEW.dateDebutVote) OR (:NEW.dateFinQuestion > :NEW.dateFinVote)) THEN
			RAISE_APPLICATION_ERROR(-20104, 'Les dates sont mauvaises !');
		ELSIF ((isTitre >= 1) AND (isDesc >= 1)) THEN
			RAISE_APPLICATION_ERROR(-20105, 'Cette question existe déjà');
		ELSE
		    IF questActuel != 0 THEN
		        SELECT NBQUESTRESTANT INTO ScoreQuestion FROM UTILISATEURS WHERE LOGIN = :NEW.LOGIN_ORGANISATEUR;
		        IF ScoreQuestion > -1 AND ScoreQuestion < 2 THEN
                    UPDATE UTILISATEURS SET UTILISATEURS.NBQUESTRESTANT = UTILISATEURS.NBQUESTRESTANT - 1 WHERE LOGIN = :NEW.LOGIN_ORGANISATEUR;
                END IF;
		    END IF;
		END IF;
	END;


    -- TRIGGER AJOUT AUTOMATIQUE ORGANISATEUR QUAND QUESTION CREEE
    CREATE OR REPLACE TRIGGER organisateur_ajoutAutomatique
    BEFORE INSERT ON Questions
    FOR EACH ROW
    DECLARE
        isAlreadyIn NUMBER;
    BEGIN
        SELECT COUNT(*) INTO isAlreadyIn FROM ORGANISATEURS WHERE login = :NEW.LOGIN_ORGANISATEUR;
        IF isAlreadyIn < 1 THEN
            AjouterOrganisateurs(:NEW.LOGIN_ORGANISATEUR);
        END IF;
    END;



	-- ###############################################
	-- PROCEDURES AJOUT

		CREATE OR REPLACE PROCEDURE AjouterQuestions(p_visibilite Questions.visibilite%TYPE, p_titre Questions.titre%TYPE, p_description Questions.description%TYPE, p_dateDebutQuestion Questions.dateDebutQuestion%TYPE, p_dateFinQuestion Questions.dateFinQuestion%TYPE, p_dateDebutVote Questions.dateDebutVote%TYPE, p_dateFinVote Questions.dateFinVote%TYPE, p_loginOrganisateur Questions.LOGIN_ORGANISATEUR%TYPE, p_loginSpecialiste Questions.LOGIN_SPECIALISTE%TYPE, p_typeVote Questions.typeVote%TYPE) IS
		    idQuestionHere NUMBER := questions_seq.nextval;
		BEGIN
		    IF p_loginSpecialiste IS NOT NULL THEN
                AjouterSpecialistes(p_loginSpecialiste);
            END IF;
			INSERT INTO Questions(idQuestion, typeVote, visibilite, titre, description, dateDebutQuestion, dateFinQuestion, dateDebutVote, dateFinVote, LOGIN_ORGANISATEUR, LOGIN_SPECIALISTE)
			VALUES(idQuestionHere, p_typeVote, p_visibilite, p_titre, p_description, p_dateDebutQuestion, p_dateFinQuestion, p_dateDebutVote, p_dateFinVote, p_loginOrganisateur, p_loginSpecialiste);
			AJOUTERVOTANTAQUESTION(idQuestionHere, p_loginOrganisateur);
		END;


		CREATE OR REPLACE PROCEDURE AjouterSections(p_titreSection Sections.titreSection%TYPE, p_idQuestion Questions.idQuestion%TYPE, p_descriptionSection Sections.descriptionSection%TYPE) IS
		BEGIN
				INSERT INTO Sections(idSection, titreSection, idQuestion, DESCRIPTIONSECTION) VALUES(sections_seq.NEXTVAL, p_titreSection, p_idQuestion, p_descriptionSection);
		END;


		CREATE OR REPLACE PROCEDURE AjouterPropositions(p_visibiliteProposition Propositions.visibiliteProposition%TYPE, p_titreProposition Propositions.titreProposition%TYPE) IS
        BEGIN
            INSERT INTO Propositions(IDPROPOSITION, VISIBILITEPROPOSITION, IDPROPFUSIONPARENT, TITREPROPOSITION) VALUES(propositions_seq.NEXTVAL, p_visibiliteProposition, null, p_titreProposition);
        END;


		CREATE OR REPLACE PROCEDURE AjouterRedigerCA(p_login CoAuteurs.login%TYPE, p_idProposition Propositions.idProposition%TYPE) IS
			isAlreadyResp NUMBER;
			isInsideCoAuth NUMBER;
			idQuestionProp NUMBER;
			isRespAnywhere NUMBER;
		BEGIN
			SELECT COUNT(login) INTO isAlreadyResp FROM RedigerR WHERE login = p_login AND idProposition = p_idProposition;
			SELECT COUNT(login) INTO isInsideCoAuth FROM CoAuteurs WHERE login = p_login;
			SELECT DISTINCT IDQUESTION INTO idQuestionProp FROM RECEVOIR WHERE IDPROPOSITION = p_idProposition;
			SELECT COUNT(DISTINCT login) INTO isRespAnywhere FROM Recevoir r JOIN PROPOSITIONS p ON r.IDPROPOSITION = p.IDPROPOSITION
			JOIN RedigerR rr ON r.idProposition = rr.idProposition WHERE idQuestion = idQuestionProp AND login = p_login AND VISIBILITEPROPOSITION = 'visible';

			IF isAlreadyResp >= 1 THEN
				RAISE_APPLICATION_ERROR(-20106, 'Cet Utilisateur est déjà Responsable sur cette proposition !');
			ELSIF isRespAnywhere >= 1 THEN
				RAISE_APPLICATION_ERROR(-20110, 'Cet Utilisateur est déjà Responsable sur cette question !');
			ELSE
				IF isInsideCoAuth < 1 THEN
					AjouterCoAuteurs(p_login);
					INSERT INTO RedigerCA VALUES(p_login, p_idProposition);
				ELSE
					INSERT INTO RedigerCA VALUES(p_login, p_idProposition);
				END IF;
				AJOUTERVOTANTAQUESTION(idQuestionProp, p_login);
			END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterRedigerR(p_login Responsables.login%TYPE, p_idProposition Propositions.idProposition%TYPE, p_idQuestion Questions.idQuestion%TYPE) IS
			isAlreadyCoAuth NUMBER;
			propositionActuel NUMBER;

			isInAnotherProp NUMBER;
			isTheOtherPropVisible NUMBER;

			loginOrgaQuestion VARCHAR(50);
			ScoreProposition NUMBER;
		BEGIN
			-- ############## PREMIERE PARTIE ##############
			SELECT COUNT(*) INTO isAlreadyCoAuth FROM RedigerCA WHERE login = p_login AND idProposition = p_idProposition;

			-- ############## DEUXIEME PARTIE ##############
			SELECT COUNT(*) INTO isInAnotherProp FROM RedigerR rr JOIN Propositions p ON rr.idProposition = p.idProposition JOIN Recevoir r ON p.idProposition = r.idProposition WHERE login = p_login AND r.idQuestion = p_idQuestion;
			SELECT COUNT(*) INTO isTheOtherPropVisible FROM RedigerR rr JOIN Propositions p ON rr.idProposition = p.idProposition JOIN Recevoir r ON p.idProposition = r.idProposition WHERE visibiliteProposition = 'visible' AND login = p_login AND r.idQuestion = p_idQuestion;
			SELECT LOGIN_ORGANISATEUR INTO loginOrgaQuestion FROM QUESTIONS WHERE IDQUESTION = p_idQuestion;


			IF isAlreadyCoAuth >= 1 THEN
				RAISE_APPLICATION_ERROR(-20107, 'Cet Utilisateur est déjà CoAuteur sur cette proposition !');
			ELSE
                AjouterResponsables(p_login);
                INSERT INTO RedigerR(LOGIN, IDPROPOSITION) VALUES(p_login, p_idProposition);

                IF loginOrgaQuestion != p_login THEN
                    SELECT NBPROPRESTANT INTO propositionActuel FROM SCOREPROPOSITIONS WHERE LOGIN = p_login AND IDQUESTION = p_idQuestion;
                    IF propositionActuel != 0 THEN
                        SELECT NBPROPRESTANT INTO ScoreProposition FROM SCOREPROPOSITIONS WHERE IDQUESTION = p_idQuestion AND LOGIN = p_login;
                        IF ScoreProposition > -1 AND ScoreProposition < 2 THEN
                            UPDATE SCOREPROPOSITIONS SET NBPROPRESTANT = NBPROPRESTANT - 1 WHERE LOGIN = p_login AND IDQUESTION = p_idQuestion;
                        END IF;
                    END IF;
                ELSE
                    IF isInAnotherProp != 0 THEN
                        IF isTheOtherPropVisible > 0 THEN
                            RAISE_APPLICATION_ERROR(-20108, 'Cet Utilisateur est déjà Responsable dans une autre Proposition de cette même Question');
                        END IF;
                    END IF;
                END IF;
			END IF;
		END;


		CREATE OR REPLACE PROCEDURE AjouterRepPropRedigerR(p_login Responsables.login%TYPE, p_idProposition Propositions.idProposition%TYPE, p_idPropositionAncien Propositions.idProposition%TYPE, p_idQuestion Questions.idQuestion%TYPE, isFusion NUMBER) IS
            fusionActuel NUMBER;
            scoreFusion NUMBER;
		BEGIN
            AjouterRedigerR(p_login, p_idProposition, p_idQuestion);
            AJOUTERVOTANTAQUESTION(p_idQuestion, p_login);

            IF p_idPropositionAncien IS NOT NULL THEN
		        SELECT NBFUSIONRESTANT INTO fusionActuel FROM SCOREFUSION WHERE LOGIN = p_login AND idProposition = p_idPropositionAncien;
		    ELSE
                SELECT NBFUSIONRESTANT INTO fusionActuel FROM SCOREFUSION WHERE LOGIN = p_login AND idProposition IS NULL;
		    END IF;
            IF isFusion = 1 THEN
                IF fusionActuel != 0 THEN
                    SELECT NBFUSIONRESTANT INTO scoreFusion FROM SCOREFUSION WHERE login = p_login AND idProposition = p_idProposition;
                    IF scoreFusion > -1 AND scoreFusion < 2 THEN
                        UPDATE SCOREFUSION SET NBFUSIONRESTANT = NBFUSIONRESTANT - 1 WHERE LOGIN = p_login AND idProposition = p_idPropositionAncien;
                    END IF;
                END IF;
            END IF;
        END;


		CREATE OR REPLACE PROCEDURE AjouterVotes(p_login Votants.login%TYPE, p_idProposition Propositions.idProposition%TYPE, p_note Voter.note%TYPE) IS
		BEGIN
		        AjouterVotants(p_login);
				INSERT INTO Voter VALUES(p_login, p_idProposition, p_note);
		END;


		CREATE OR REPLACE PROCEDURE AjouterRecevoir(p_idQuestion Questions.idQuestion%TYPE, p_idSection Sections.idSection%TYPE, p_idProposition Propositions.idProposition%TYPE, p_texte Recevoir.texte%TYPE, p_jaime Recevoir.jaime%TYPE) IS
		BEGIN
            INSERT INTO Recevoir(IDQUESTION, IDSECTION, IDPROPOSITION, TEXTE, JAIME) VALUES(p_idQuestion, p_idSection, p_idProposition, p_texte, p_jaime);
		END;


		CREATE OR REPLACE PROCEDURE AjouterCommentairesEtStocker(p_idQuestion Questions.idQuestion%TYPE, p_idProposition Propositions.idProposition%TYPE, p_numeroParagraphe Commentaires.numeroParagraphe%TYPE, p_indexCharDebut Commentaires.indexCharDebut%TYPE, p_indexCharFin Commentaires.indexCharFin%TYPE, p_texteCommentaire Commentaires.texteCommentaire%TYPE) IS
		    idCom NUMBER := commentaires_seq.NEXTVAL;
		    isAlreadyExisting NUMBER;
		BEGIN
		    SELECT COUNT(IDCOMMENTAIRE) INTO isAlreadyExisting FROM COMMENTAIRES
		        WHERE NUMEROPARAGRAPHE = p_numeroParagraphe
		        AND INDEXCHARDEBUT = p_indexCharDebut AND INDEXCHARFIN = p_indexCharFin;

		    IF isAlreadyExisting > 0 THEN
                RAISE_APPLICATION_ERROR(-20109, 'Ce commentaire existe déjà');
            END IF;

            INSERT INTO Commentaires(IDCOMMENTAIRE, NUMEROPARAGRAPHE, INDEXCHARDEBUT, INDEXCHARFIN, TEXTECOMMENTAIRE) VALUES(idCom, p_numeroParagraphe, p_indexCharDebut, p_indexCharFin, p_texteCommentaire);
            INSERT INTO Stocker(IDQUESTION, IDPROPOSITION, IDCOMMENTAIRE) VALUES(p_idQuestion, p_idProposition, idCom);
        END;



	-- ###############################################
	-- PROCEDURES MODIFIER

		CREATE OR REPLACE PROCEDURE ModifierQuestions(p_idQuestion Questions.idQuestion%TYPE, p_visibilite Questions.visibilite%TYPE, p_description Questions.description%TYPE) IS
		BEGIN
			UPDATE Questions SET visibilite = p_visibilite, description = p_description
			WHERE idQuestion = p_idQuestion;
		END;


		CREATE OR REPLACE PROCEDURE ModifierHeureQuestion(p_idQuestion Questions.idQuestion%TYPE, p_dateDebutQuestion Questions.dateDebutQuestion%TYPE, p_dateFinQuestion Questions.dateFinQuestion%TYPE, p_dateDebutVote Questions.dateDebutVote%TYPE, p_dateFinVote Questions.dateFinVote%TYPE) IS
        BEGIN
            UPDATE Questions SET dateDebutQuestion = p_dateDebutQuestion, dateFinQuestion = p_dateFinQuestion, dateDebutVote = p_dateDebutVote, dateFinVote = p_dateFinVote
            WHERE idQuestion = p_idQuestion;
        END;


		CREATE OR REPLACE PROCEDURE ModifierSections(p_idSection Sections.idSection%TYPE, p_titreSection Sections.titreSection%TYPE, p_idQuestion Questions.idQuestion%TYPE, p_descriptionSection Sections.descriptionSection%TYPE) IS
		BEGIN
			UPDATE Sections SET titreSection = p_titreSection, DESCRIPTIONSECTION = p_descriptionSection
			WHERE idSection = p_idSection
			AND idQuestion = p_idQuestion;
		END;


		CREATE OR REPLACE PROCEDURE ModifierVotes(p_login Votants.login%TYPE, p_idProposition Propositions.idProposition%TYPE, p_note Voter.note%TYPE) IS
		BEGIN
			UPDATE Voter SET note = p_note
			WHERE login = p_login
			AND idProposition = p_idProposition;
		END;


		CREATE OR REPLACE PROCEDURE ModifierRecevoir(p_idQuestion Questions.idQuestion%TYPE, p_idSection Sections.idSection%TYPE, p_idProposition Propositions.idProposition%TYPE, p_texte Recevoir.texte%TYPE, p_jaime Recevoir.jaime%TYPE) IS
		BEGIN
			UPDATE Recevoir SET texte = p_texte, jaime = p_jaime
			WHERE idSection = p_idSection
			AND idProposition = p_idProposition
			AND idQuestion = p_idQuestion;
		END;


		CREATE OR REPLACE PROCEDURE ModifierPropositions(p_idProposition Propositions.idProposition%TYPE, p_visibiliteProposition Propositions.visibiliteProposition%TYPE, p_idPropFusionParent PROPOSITIONS.idPropFusionParent%TYPE, p_titreProposition Propositions.titreProposition%TYPE) IS
        BEGIN
            UPDATE Propositions SET visibiliteProposition = p_visibiliteProposition, idPropFusionParent = p_idPropFusionParent, TITREPROPOSITION = p_titreProposition WHERE idProposition = p_idProposition;
        END;


		CREATE OR REPLACE PROCEDURE ModifierCommentaires(p_idCommentaire Commentaires.idCommentaire%TYPE, p_numeroParagraphe Commentaires.numeroParagraphe%TYPE, p_indexCharDebut Commentaires.indexCharDebut%TYPE, p_indexCharFin Commentaires.indexCharFin%TYPE, p_texteCommentaire Commentaires.texteCommentaire%TYPE) IS
        BEGIN
            UPDATE Commentaires SET NUMEROPARAGRAPHE = p_numeroParagraphe, INDEXCHARDEBUT = p_indexCharDebut, INDEXCHARFIN = p_indexCharFin, TEXTECOMMENTAIRE = p_texteCommentaire
            WHERE IDCOMMENTAIRE = p_idCommentaire;
        END;



	-- ###############################################
	-- PROCEDURES SUPPRESSION

		CREATE OR REPLACE PROCEDURE SupprimerQuestions(p_idQuestion Questions.idQuestion%TYPE) IS
        BEGIN
            DELETE FROM Effectuer WHERE idQuestion = p_idQuestion;
            DELETE FROM STOCKER WHERE IDQUESTION = p_idQuestion;
            DELETE FROM Recevoir WHERE idQuestion = p_idQuestion;
            DELETE FROM Sections WHERE idQuestion = p_idQuestion;
            DELETE FROM Existe WHERE idQuestion = p_idQuestion;
            DELETE FROM ScorePropositions WHERE idQuestion = p_idQuestion;
            DELETE FROM EXISTERGROUPE WHERE IDQUESTION = p_idQuestion;
            DELETE FROM Questions WHERE idQuestion = p_idQuestion;
        END;


		CREATE OR REPLACE PROCEDURE SupprimerSections(p_idSection Sections.idSection%TYPE) IS
		BEGIN
			DELETE FROM Recevoir WHERE idSection = p_idSection;
			DELETE FROM Sections WHERE idSection = p_idSection;
		END;


		CREATE OR REPLACE PROCEDURE SupprimerPropositions(p_idProposition Propositions.idProposition%TYPE) IS
        BEGIN
            DELETE FROM Effectuer WHERE idProposition = p_idProposition;
            DELETE FROM RedigerCA WHERE idProposition = p_idProposition;
            DELETE FROM RedigerR WHERE idProposition = p_idProposition;
            DELETE FROM Voter WHERE idProposition = p_idProposition;
            DELETE FROM ScoreFusion WHERE idProposition = p_idProposition;
            DELETE FROM Recevoir WHERE idProposition = p_idProposition;
            DELETE FROM Stocker WHERE idProposition = p_idProposition;
            DELETE FROM Propositions WHERE idProposition = p_idProposition;
        END;


		CREATE OR REPLACE PROCEDURE SupprimerCommentaires(p_idCommentaire Commentaires.idCommentaire%TYPE) IS
        BEGIN
            DELETE FROM STOCKER WHERE IDCOMMENTAIRE = p_idCommentaire;
            DELETE FROM COMMENTAIRES WHERE IDCOMMENTAIRE = p_idCommentaire;
        END;


		CREATE OR REPLACE PROCEDURE SupprimerRedigerCA(p_login REDIGERCA.login%TYPE, p_idProposition REDIGERCA.idProposition%TYPE) IS
        BEGIN
            DELETE FROM REDIGERCA WHERE IDPROPOSITION = p_idProposition AND LOGIN = p_login;
        END;



    -- ###############################################
    -- FONCTIONS GETTER

        CREATE OR REPLACE FUNCTION GetPropositionGagnante(p_idQuestion QUESTIONS.idQuestion%TYPE) RETURN NUMBER AS
            idPropGagnant NUMBER;
        BEGIN
            SELECT IDPROPOSITION INTO idPropGagnant FROM (SELECT SUM(V.NOTE) AS TOTAL, R.IDPROPOSITION FROM RECEVOIR R
                JOIN VOTER V on R.IDPROPOSITION = V.IDPROPOSITION
                WHERE R.IDQUESTION = p_idQuestion
                GROUP BY R.IDPROPOSITION, R.IDQUESTION) s
            WHERE s.TOTAL = (SELECT MAX(TOTAL2) FROM (SELECT SUM(V.NOTE) AS TOTAL2 FROM RECEVOIR R
                JOIN VOTER V on R.IDPROPOSITION = V.IDPROPOSITION
                WHERE R.IDQUESTION = p_idQuestion
                GROUP BY R.IDPROPOSITION, R.IDQUESTION));
            RETURN idPropGagnant;
        END;


        CREATE OR REPLACE FUNCTION estOrganisateur(p_login Roles.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
        BEGIN
            SELECT COUNT(IDQUESTION) INTO v_nb FROM Questions WHERE idQuestion = p_idQuestion AND LOGIN_ORGANISATEUR = p_login;
            IF v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;


        CREATE OR REPLACE FUNCTION estResponsable(p_login Roles.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
        BEGIN
            SELECT COUNT(q.IDQUESTION) INTO v_nb FROM Questions q
                JOIN Recevoir r ON q.idQuestion = r.idQuestion
                JOIN RedigerR rr ON r.idProposition = rr.idProposition
                WHERE q.idQuestion = p_idQuestion AND rr.login = p_login;
            IF v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;


        CREATE OR REPLACE FUNCTION estCoAuteur(p_login Roles.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
        BEGIN
            SELECT COUNT(q.IDQUESTION) INTO v_nb FROM Questions q
                JOIN Recevoir r ON q.idQuestion = r.idQuestion
                JOIN RedigerCA rc ON r.idProposition = rc.idProposition
                WHERE q.idQuestion = p_idQuestion AND rc.login = p_login;
            IF v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;


        CREATE OR REPLACE FUNCTION estVotant(p_login Roles.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
            estDansGroupe NUMBER;
        BEGIN
            SELECT COUNT(IDQUESTION) INTO v_nb FROM EXISTE WHERE IDQUESTION = p_idQuestion AND LOGIN = p_login;
            SELECT COUNT(IDQUESTION) INTO estDansGroupe FROM EXISTERGROUPE eg JOIN APPARTENIR a on eg.IDGROUPE = a.IDGROUPE WHERE IDQUESTION = p_idQuestion AND LOGIN = p_login;

            IF estDansGroupe > 0 OR v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;


        CREATE OR REPLACE FUNCTION estSpecialiste(p_login SPECIALISTE.login%TYPE, p_idQuestion Questions.idQuestion%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
        BEGIN
            SELECT COUNT(IDQUESTION) INTO v_nb FROM QUESTIONS WHERE IDQUESTION = p_idQuestion AND LOGIN_SPECIALISTE = p_login;
            IF v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;


        CREATE OR REPLACE FUNCTION estResponsableProp(p_login Roles.login%TYPE, p_idProposition Propositions.idProposition%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
        BEGIN
            SELECT COUNT(p.IDPROPOSITION) INTO v_nb FROM Propositions p
                JOIN RedigerR rr ON p.idProposition = rr.idProposition
                WHERE p.idProposition = p_idProposition AND rr.login = p_login;
            IF v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;


        CREATE OR REPLACE FUNCTION estCoAuteurProp(p_login Roles.login%TYPE, p_idProposition Propositions.idProposition%TYPE) RETURN NUMBER IS
            v_nb NUMBER;
        BEGIN
            SELECT COUNT(p.IDPROPOSITION) INTO v_nb FROM Propositions p
            JOIN RedigerCA rc ON p.idProposition = rc.idProposition
            WHERE p.idProposition = p_idProposition AND rc.login = p_login;
            IF v_nb > 0 THEN
                RETURN 1;
            ELSE
                RETURN NULL;
            END IF;
        END;