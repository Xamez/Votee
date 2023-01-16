ALTER SESSION SET NLS_TIMESTAMP_FORMAT='YYYY-MM-DD HH24:MI:SS.FF';

CREATE TABLE Utilisateurs(
   login VARCHAR(50),
   motDePasse VARCHAR(255) NOT NULL,
   nom VARCHAR(2000) NOT NULL,
   prenom VARCHAR(2000) NOT NULL,
   nbQuestRestant INT NOT NULL,
   description VARCHAR(2000) NOT NULL,
   PRIMARY KEY(login)
);

CREATE TABLE Administrateurs(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Utilisateurs(login)
);

CREATE TABLE Roles(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Utilisateurs(login)
);

CREATE TABLE Propositions(
   idProposition INT,
   visibiliteProposition VARCHAR(50) NOT NULL,
   idPropFusionParent INT,
   titreProposition VARCHAR(2000) NOT NULL,
   PRIMARY KEY(idProposition)
);

CREATE TABLE Demandes(
   idDemande INT,
   PRIMARY KEY(idDemande)
);

CREATE TABLE Commentaires(
   idCommentaire INT,
   numeroParagraphe INT NOT NULL,
   indexCharDebut INT NOT NULL,
   indexCharFin INT NOT NULL,
   texteCommentaire VARCHAR(300) NOT NULL,
   PRIMARY KEY(idCommentaire)
);

CREATE TABLE Groupes(
   idGroupe INT,
   nomGroupe VARCHAR(2000) NOT NULL,
   PRIMARY KEY(idGroupe)
);

CREATE TABLE Specialiste(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Roles(login)
);

CREATE TABLE Responsables(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Roles(login)
);

CREATE TABLE Organisateurs(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Roles(login)
);

CREATE TABLE CoAuteurs(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Roles(login)
);

CREATE TABLE Votants(
   login VARCHAR(50),
   PRIMARY KEY(login),
   FOREIGN KEY(login) REFERENCES Roles(login)
);

CREATE TABLE Questions(
   idQuestion INT,
   typeVote VARCHAR(50) NOT NULL,
   visibilite VARCHAR(50) NOT NULL,
   titre VARCHAR(2000) NOT NULL,
   description VARCHAR(2000) NOT NULL,
   dateDebutQuestion TIMESTAMP NOT NULL,
   dateFinQuestion TIMESTAMP NOT NULL,
   dateDebutVote TIMESTAMP NOT NULL,
   dateFinVote TIMESTAMP NOT NULL,
   login_specialiste VARCHAR(50),
   login_organisateur VARCHAR(50) NOT NULL,
   PRIMARY KEY(idQuestion),
   FOREIGN KEY(login_specialiste) REFERENCES Specialiste(login),
   FOREIGN KEY(login_organisateur) REFERENCES Organisateurs(login)
);

CREATE TABLE Sections(
   idSection INT,
   titreSection VARCHAR(2000) NOT NULL,
   descriptionSection VARCHAR(2000) NOT NULL,
   idQuestion INT NOT NULL,
   PRIMARY KEY(idSection),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion)
);


CREATE TABLE Voter(
   login VARCHAR(50),
   idProposition INT,
   note INT,
   PRIMARY KEY(login, idProposition),
   FOREIGN KEY(login) REFERENCES Votants(login),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition)
);

CREATE TABLE Effectuer(
   loginDestinataire VARCHAR(50),
   login VARCHAR(50),
   idDemande INT,
   etatDemande VARCHAR(50) NOT NULL,
   titreDemande VARCHAR(15) NOT NULL,
   texteDemande VARCHAR(2000) NOT NULL,
   idQuestion INT,
   idProposition INT,
   PRIMARY KEY(loginDestinataire, login, idDemande),
   FOREIGN KEY(loginDestinataire) REFERENCES Utilisateurs(login),
   FOREIGN KEY(login) REFERENCES Utilisateurs(login),
   FOREIGN KEY(idDemande) REFERENCES Demandes(idDemande),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition)
);

CREATE TABLE RedigerCA(
   login VARCHAR(50),
   idProposition INT,
   PRIMARY KEY(login, idProposition),
   FOREIGN KEY(login) REFERENCES CoAuteurs(login),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition)
);

CREATE TABLE RedigerR(
   login VARCHAR(50),
   idProposition INT,
   PRIMARY KEY(login, idProposition),
   FOREIGN KEY(login) REFERENCES Responsables(login),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition)
);

CREATE TABLE Recevoir(
   idQuestion INT,
   idSection INT,
   idProposition INT,
   texte VARCHAR(2000),
   jaime INT,
   PRIMARY KEY(idQuestion, idSection, idProposition),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion),
   FOREIGN KEY(idSection) REFERENCES Sections(idSection),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition)
);

CREATE TABLE Stocker(
   idQuestion INT,
   idProposition INT,
   idCommentaire INT,
   PRIMARY KEY(idQuestion, idProposition, idCommentaire),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition),
   FOREIGN KEY(idCommentaire) REFERENCES Commentaires(idCommentaire)
);

CREATE TABLE ScorePropositions(
   login VARCHAR(50),
   idQuestion INT,
   nbPropRestant INT NOT NULL,
   PRIMARY KEY(login, idQuestion),
   FOREIGN KEY(login) REFERENCES Utilisateurs(login),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion)
);

CREATE TABLE ScoreFusion(
   login VARCHAR(50),
   idProposition INT,
   nbFusionRestant INT NOT NULL,
   PRIMARY KEY(login, idProposition),
   FOREIGN KEY(login) REFERENCES Utilisateurs(login),
   FOREIGN KEY(idProposition) REFERENCES Propositions(idProposition)
);

CREATE TABLE Existe(
   login VARCHAR(50),
   idQuestion INT,
   PRIMARY KEY(login, idQuestion),
   FOREIGN KEY(login) REFERENCES Votants(login),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion)
);

CREATE TABLE Appartenir(
   login VARCHAR(50),
   idGroupe INT,
   PRIMARY KEY(login, idGroupe),
   FOREIGN KEY(login) REFERENCES Utilisateurs(login),
   FOREIGN KEY(idGroupe) REFERENCES Groupes(idGroupe)
);

CREATE TABLE ExisterGroupe(
   idQuestion INT,
   idGroupe INT,
   PRIMARY KEY(idQuestion, idGroupe),
   FOREIGN KEY(idQuestion) REFERENCES Questions(idQuestion),
   FOREIGN KEY(idGroupe) REFERENCES Groupes(idGroupe)
);
