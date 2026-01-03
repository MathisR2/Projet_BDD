-- Données d'exemple pour tester l'application ASCG
USE ascg_db;

-- Insertion des saisons
INSERT INTO SAISON (annee_saison, date_debut, date_fin) VALUES
(2024, '2024-09-01', '2025-06-30'),
(2025, '2025-09-01', '2026-06-30');

-- Insertion des bénévoles
INSERT INTO BENEVOLE (nom_ben, prenom_ben, telephone_ben, email_ben) VALUES
('Martin', 'Sophie', '0476123456', 'sophie.martin@email.com'),
('Dubois', 'Pierre', '0476234567', 'pierre.dubois@email.com'),
('Moreau', 'Marie', '0476345678', 'marie.moreau@email.com'),
('Bernard', 'Jean', '0476456789', 'jean.bernard@email.com'),
('Petit', 'Claire', '0476567890', 'claire.petit@email.com');

-- Insertion des sections
INSERT INTO SECTION (code_section, nom_section, description_section, num_referent) VALUES
('FOOT', 'Football', 'Section football avec équipes jeunes et seniors', 1),
('TENNIS', 'Tennis', 'Club de tennis avec courts couverts et extérieurs', 2),
('NATATION', 'Natation', 'École de natation et aquagym', 3),
('DANSE', 'Danse', 'Cours de danse moderne, classique et hip-hop', 4),
('YOGA', 'Yoga & Bien-être', 'Cours de yoga, pilates et relaxation', 5);

-- Insertion des activités
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

-- Insertion des adhérents
INSERT INTO ADHERENT (nom_adh, prenom_adh, date_naissance, adresse_adh, code_postal, ville_adh, telephone_adh, email_adh) VALUES
('Dupont', 'Lucas', '2010-03-15', '12 rue des Fleurs', '38000', 'Grenoble', '0476111111', 'lucas.dupont@email.com'),
('Durand', 'Emma', '2008-07-22', '25 avenue Victor Hugo', '38100', 'Grenoble', '0476222222', 'emma.durand@email.com'),
('Leroy', 'Thomas', '1985-11-08', '8 place de la République', '38000', 'Grenoble', '0476333333', 'thomas.leroy@email.com'),
('Roux', 'Camille', '1992-04-12', '33 boulevard Gambetta', '38000', 'Grenoble', '0476444444', 'camille.roux@email.com'),
('Blanc', 'Julien', '2012-09-30', '15 rue Jean Jaurès', '38100', 'Grenoble', '0476555555', 'julien.blanc@email.com'),
('Garnier', 'Léa', '1988-01-18', '7 impasse des Roses', '38000', 'Grenoble', '0476666666', 'lea.garnier@email.com'),
('Faure', 'Antoine', '2009-12-05', '42 rue de la Paix', '38100', 'Grenoble', '0476777777', 'antoine.faure@email.com'),
('Girard', 'Manon', '1995-06-28', '18 avenue Alsace Lorraine', '38000', 'Grenoble', '0476888888', 'manon.girard@email.com');

-- Insertion des compositions de bureau
INSERT INTO COMPOSITION_BUREAU (num_benevole, code_section, annee_saison, fonction) VALUES
(1, 'FOOT', 2024, 'Président'),
(2, 'TENNIS', 2024, 'Président'),
(3, 'NATATION', 2024, 'Président'),
(4, 'DANSE', 2024, 'Président'),
(5, 'YOGA', 2024, 'Président'),
(1, 'FOOT', 2024, 'Entraîneur'),
(2, 'TENNIS', 2024, 'Professeur'),
(3, 'NATATION', 2024, 'Maître nageur');

-- Insertion des liens de parenté
INSERT INTO PARENTE (num_adherent1, num_adherent2, type_lien) VALUES
(1, 5, 'frère'),  -- Lucas et Julien sont frères
(3, 6, 'conjoint'), -- Thomas et Léa sont conjoints
(2, 7, 'soeur');   -- Emma et Antoine sont frère et soeur

-- Insertion des inscriptions
INSERT INTO INSCRIPTION (num_adherent, code_activite, annee_saison, date_inscription, montant_paye, numero_cheque, date_encaissement) VALUES
(1, 'FOOT_JU', 2024, '2024-09-01', 180.00, 'CHQ001', '2024-09-15'),
(2, 'TENNIS_JU', 2024, '2024-09-02', 200.00, 'CHQ002', '2024-09-15'),
(3, 'TENNIS_AD', 2024, '2024-09-03', 250.00, 'CHQ003', '2024-09-15'),
(4, 'DANSE_MOD', 2024, '2024-09-04', 190.00, 'CHQ004', NULL),
(5, 'FOOT_JU', 2024, '2024-09-05', 162.00, 'CHQ005', NULL), -- Remise famille 10%
(6, 'YOGA_HAT', 2024, '2024-09-06', 150.00, 'CHQ006', '2024-09-20'),
(7, 'NAT_ECOLE', 2024, '2024-09-07', 160.00, 'CHQ007', NULL),
(8, 'HIP_HOP', 2024, '2024-09-08', 170.00, 'CHQ008', '2024-09-20'),
(1, 'TENNIS_JU', 2024, '2024-09-10', 200.00, 'CHQ009', NULL), -- Lucas fait aussi du tennis
(4, 'PILATES', 2024, '2024-09-12', 160.00, 'CHQ010', NULL);  -- Camille fait aussi du pilates