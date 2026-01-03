-- Données d'exemple pour ASCG
USE ascg_db;

-- Bénévoles
INSERT INTO BENEVOLE (nom_ben, prenom_ben, telephone_ben, email_ben) VALUES
('Martin', 'Jean', '0123456789', 'jean.martin@email.com'),
('Dubois', 'Marie', '0234567890', 'marie.dubois@email.com'),
('Leroy', 'Pierre', '0345678901', 'pierre.leroy@email.com');

-- Sections
INSERT INTO SECTION (code_section, nom_section, description_section, num_referent) VALUES
('FOOT', 'Football', 'Section football de l\'ASCG', 1),
('TENNIS', 'Tennis', 'Section tennis de l\'ASCG', 2),
('NATATION', 'Natation', 'Section natation de l\'ASCG', 3);

-- Activités
INSERT INTO ACTIVITE (code_activite, nom_activite, description_activite, tarif_activite, code_section) VALUES
('FOOT_ADU', 'Football Adultes', 'Football pour adultes', 150.00, 'FOOT'),
('FOOT_ENF', 'Football Enfants', 'Football pour enfants', 100.00, 'FOOT'),
('TENNIS_ADU', 'Tennis Adultes', 'Tennis pour adultes', 200.00, 'TENNIS'),
('NAT_ADU', 'Natation Adultes', 'Natation pour adultes', 120.00, 'NATATION');

-- Saisons
INSERT INTO SAISON (annee_saison, date_debut, date_fin) VALUES
(2024, '2024-09-01', '2025-06-30'),
(2025, '2025-09-01', '2026-06-30');

-- Adhérents
INSERT INTO ADHERENT (nom_adh, prenom_adh, date_naissance, adresse_adh, code_postal, ville_adh, telephone_adh, email_adh) VALUES
('Dupont', 'Paul', '1985-03-15', '123 rue de la Paix', '38000', 'Grenoble', '0456789012', 'paul.dupont@email.com'),
('Moreau', 'Sophie', '1990-07-22', '456 avenue Victor Hugo', '38100', 'Grenoble', '0567890123', 'sophie.moreau@email.com');