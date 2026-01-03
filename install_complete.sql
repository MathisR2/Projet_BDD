-- Base de données ASCG - Installation complète
CREATE DATABASE IF NOT EXISTS ascg_db;
USE ascg_db;

-- Suppression des tables si elles existent (pour réinstallation)
DROP TABLE IF EXISTS PARENTE;
DROP TABLE IF EXISTS COMPOSITION_BUREAU;
DROP TABLE IF EXISTS INSCRIPTION;
DROP TABLE IF EXISTS ACTIVITE;
DROP TABLE IF EXISTS SECTION;
DROP TABLE IF EXISTS SAISON;
DROP TABLE IF EXISTS BENEVOLE;
DROP TABLE IF EXISTS ADHERENT;

-- Création des tables
CREATE TABLE ADHERENT (
    num_adherent INT AUTO_INCREMENT PRIMARY KEY,
    nom_adh VARCHAR(50) NOT NULL,
    prenom_adh VARCHAR(50) NOT NULL,
    date_naissance DATE NOT NULL,
    adresse_adh VARCHAR(100) NOT NULL,
    code_postal CHAR(5) NOT NULL,
    ville_adh VARCHAR(50) NOT NULL,
    telephone_adh VARCHAR(15),
    email_adh VARCHAR(100),
    CONSTRAINT chk_code_postal CHECK (code_postal REGEXP '^[0-9]{5}$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE BENEVOLE (
    num_benevole INT AUTO_INCREMENT PRIMARY KEY,
    nom_ben VARCHAR(50) NOT NULL,
    prenom_ben VARCHAR(50) NOT NULL,
    telephone_ben VARCHAR(15) NOT NULL,
    email_ben VARCHAR(100) NOT NULL,
    CONSTRAINT chk_email_ben UNIQUE (email_ben)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SECTION (
    code_section VARCHAR(10) PRIMARY KEY,
    nom_section VARCHAR(100) NOT NULL,
    description_section TEXT,
    num_referent INT NOT NULL,
    CONSTRAINT fk_section_referent FOREIGN KEY (num_referent) 
        REFERENCES BENEVOLE(num_benevole)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ACTIVITE (
    code_activite VARCHAR(10) PRIMARY KEY,
    nom_activite VARCHAR(100) NOT NULL,
    description_activite TEXT,
    tarif_activite DECIMAL(10,2) NOT NULL,
    code_section VARCHAR(10) NOT NULL,
    CONSTRAINT fk_activite_section FOREIGN KEY (code_section)
        REFERENCES SECTION(code_section)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_tarif_positif CHECK (tarif_activite > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SAISON (
    annee_saison INT PRIMARY KEY,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    CONSTRAINT chk_dates_saison CHECK (date_fin > date_debut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE INSCRIPTION (
    num_adherent INT,
    code_activite VARCHAR(10),
    annee_saison INT,
    date_inscription DATE NOT NULL,
    montant_paye DECIMAL(10,2) NOT NULL,
    numero_cheque VARCHAR(20),
    date_encaissement DATE,
    PRIMARY KEY (num_adherent, code_activite, annee_saison),
    CONSTRAINT fk_inscription_adherent FOREIGN KEY (num_adherent)
        REFERENCES ADHERENT(num_adherent)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_inscription_activite FOREIGN KEY (code_activite)
        REFERENCES ACTIVITE(code_activite)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_inscription_saison FOREIGN KEY (annee_saison)
        REFERENCES SAISON(annee_saison)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT chk_montant_positif CHECK (montant_paye > 0),
    CONSTRAINT chk_dates_encaissement CHECK (date_encaissement IS NULL OR date_encaissement >= date_inscription)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE COMPOSITION_BUREAU (
    num_benevole INT,
    code_section VARCHAR(10),
    annee_saison INT,
    fonction VARCHAR(50) NOT NULL,
    PRIMARY KEY (num_benevole, code_section, annee_saison),
    CONSTRAINT fk_bureau_benevole FOREIGN KEY (num_benevole)
        REFERENCES BENEVOLE(num_benevole)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_bureau_section FOREIGN KEY (code_section)
        REFERENCES SECTION(code_section)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_bureau_saison FOREIGN KEY (annee_saison)
        REFERENCES SAISON(annee_saison)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PARENTE (
    num_adherent1 INT,
    num_adherent2 INT,
    type_lien VARCHAR(30) NOT NULL,
    PRIMARY KEY (num_adherent1, num_adherent2),
    CONSTRAINT fk_parente_adherent1 FOREIGN KEY (num_adherent1)
        REFERENCES ADHERENT(num_adherent)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_parente_adherent2 FOREIGN KEY (num_adherent2)
        REFERENCES ADHERENT(num_adherent)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT chk_adherents_differents CHECK (num_adherent1 < num_adherent2),
    CONSTRAINT chk_type_lien CHECK (type_lien IN ('père', 'mère', 'fils', 'fille', 'conjoint', 'frère', 'soeur'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion des données de test
INSERT INTO SAISON (annee_saison, date_debut, date_fin) VALUES
(2024, '2024-09-01', '2025-06-30'),
(2025, '2025-09-01', '2026-06-30');

INSERT INTO BENEVOLE (nom_ben, prenom_ben, telephone_ben, email_ben) VALUES
('Martin', 'Sophie', '0476123456', 'sophie.martin@email.com'),
('Dubois', 'Pierre', '0476234567', 'pierre.dubois@email.com'),
('Moreau', 'Marie', '0476345678', 'marie.moreau@email.com'),
('Bernard', 'Jean', '0476456789', 'jean.bernard@email.com'),
('Petit', 'Claire', '0476567890', 'claire.petit@email.com');

INSERT INTO SECTION (code_section, nom_section, description_section, num_referent) VALUES
('FOOT', 'Football', 'Section football avec équipes jeunes et seniors', 1),
('TENNIS', 'Tennis', 'Club de tennis avec courts couverts et extérieurs', 2),
('NATATION', 'Natation', 'École de natation et aquagym', 3),
('DANSE', 'Danse', 'Cours de danse moderne, classique et hip-hop', 4),
('YOGA', 'Yoga & Bien-être', 'Cours de yoga, pilates et relaxation', 5);

INSERT INTO ACTIVITE (code_activite, nom_activite, description_activite, tarif_activite, code_section) VALUES
('FOOT_JU', 'Football Jeunes', 'Football pour les 6-16 ans, entraînements 2 fois par semaine', 180.00, 'FOOT'),
('FOOT_SE', 'Football Seniors', 'Football adultes, championnat régional', 220.00, 'FOOT'),
('TENNIS_AD', 'Tennis Adultes', 'Cours de tennis adultes tous niveaux', 250.00, 'TENNIS'),
('TENNIS_JU', 'Tennis Jeunes', 'École de tennis pour enfants et adolescents', 200.00, 'TENNIS'),
('NAT_ECOLE', 'École de Natation', 'Apprentissage de la natation pour enfants', 160.00, 'NATATION'),
('AQUAGYM', 'Aquagym', 'Cours d\'aquagym pour adultes', 140.00, 'NATATION'),
('DANSE_MOD', 'Danse Moderne', 'Cours de danse moderne et contemporaine', 190.00, 'DANSE'),
('HIP_HOP', 'Hip-Hop', 'Cours de danse hip-hop pour tous âges', 170.00, 'DANSE'),
('YOGA_HAT', 'Hatha Yoga', 'Cours de yoga traditionnel', 150.00, 'YOGA'),
('PILATES', 'Pilates', 'Renforcement musculaire et souplesse', 160.00, 'YOGA');

INSERT INTO ADHERENT (nom_adh, prenom_adh, date_naissance, adresse_adh, code_postal, ville_adh, telephone_adh, email_adh) VALUES
('Dupont', 'Lucas', '2010-03-15', '12 rue des Fleurs', '38000', 'Grenoble', '0476111111', 'lucas.dupont@email.com'),
('Durand', 'Emma', '2008-07-22', '25 avenue Victor Hugo', '38100', 'Grenoble', '0476222222', 'emma.durand@email.com'),
('Leroy', 'Thomas', '1985-11-08', '8 place de la République', '38000', 'Grenoble', '0476333333', 'thomas.leroy@email.com'),
('Roux', 'Camille', '1992-04-12', '33 boulevard Gambetta', '38000', 'Grenoble', '0476444444', 'camille.roux@email.com'),
('Blanc', 'Julien', '2012-09-30', '15 rue Jean Jaurès', '38100', 'Grenoble', '0476555555', 'julien.blanc@email.com'),
('Garnier', 'Léa', '1988-01-18', '7 impasse des Roses', '38000', 'Grenoble', '0476666666', 'lea.garnier@email.com'),
('Faure', 'Antoine', '2009-12-05', '42 rue de la Paix', '38100', 'Grenoble', '0476777777', 'antoine.faure@email.com'),
('Girard', 'Manon', '1995-06-28', '18 avenue Alsace Lorraine', '38000', 'Grenoble', '0476888888', 'manon.girard@email.com');

INSERT INTO COMPOSITION_BUREAU (num_benevole, code_section, annee_saison, fonction) VALUES
(1, 'FOOT', 2024, 'Président'),
(2, 'TENNIS', 2024, 'Président'),
(3, 'NATATION', 2024, 'Président'),
(4, 'DANSE', 2024, 'Président'),
(5, 'YOGA', 2024, 'Président');

INSERT INTO PARENTE (num_adherent1, num_adherent2, type_lien) VALUES
(1, 5, 'frère'),
(3, 6, 'conjoint'),
(2, 7, 'soeur');

INSERT INTO INSCRIPTION (num_adherent, code_activite, annee_saison, date_inscription, montant_paye, numero_cheque, date_encaissement) VALUES
(1, 'FOOT_JU', 2024, '2024-09-01', 180.00, 'CHQ001', '2024-09-15'),
(2, 'TENNIS_JU', 2024, '2024-09-02', 200.00, 'CHQ002', '2024-09-15'),
(3, 'TENNIS_AD', 2024, '2024-09-03', 250.00, 'CHQ003', '2024-09-15'),
(4, 'DANSE_MOD', 2024, '2024-09-04', 190.00, 'CHQ004', NULL),
(5, 'FOOT_JU', 2024, '2024-09-05', 162.00, 'CHQ005', NULL),
(6, 'YOGA_HAT', 2024, '2024-09-06', 150.00, 'CHQ006', '2024-09-20'),
(7, 'NAT_ECOLE', 2024, '2024-09-07', 160.00, 'CHQ007', NULL),
(8, 'HIP_HOP', 2024, '2024-09-08', 170.00, 'CHQ008', '2024-09-20'),
(1, 'TENNIS_JU', 2024, '2024-09-10', 200.00, 'CHQ009', NULL),
(4, 'PILATES', 2024, '2024-09-12', 160.00, 'CHQ010', NULL);