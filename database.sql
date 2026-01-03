-- Base de données ASCG
CREATE DATABASE IF NOT EXISTS ascg_db;
USE ascg_db;

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