# 🏆 ASCG - Association Sportive et Culturelle de Grenoble

## 📖 Description
Application web moderne de gestion complète pour l'Association Sportive et Culturelle de Grenoble (ASCG). Interface élégante avec design responsive pour gérer adhérents, bénévoles, sections, activités et inscriptions.

## ✨ Fonctionnalités Principales

### 🎨 Interface Moderne
- **Design premium** avec dégradés et effets glassmorphism
- **Animations fluides** et transitions CSS3
- **Cartes interactives** avec effets hover
- **Responsive design** adaptatif mobile/desktop
- **Icônes Font Awesome** pour navigation intuitive

### 📊 Tableau de Bord
- **Statistiques en temps réel** avec graphiques Chart.js
- **Vue d'ensemble** de l'activité de l'association
- **Top activités** les plus populaires
- **Répartition par sections** avec graphique en secteurs

### 👥 Gestion des Adhérents
- **CRUD complet** : ajout, modification, suppression
- **Validation avancée** (code postal, email)
- **Interface en cartes** ou tableau selon préférence
- **Recherche et filtrage** des membres

### 🏃 Gestion des Activités
- **Catalogue visuel** en cartes avec tarifs
- **Association automatique** aux sections
- **Descriptions détaillées** et images
- **Gestion des tarifs** avec calculs automatiques

### 📋 Gestion des Inscriptions
- **Processus d'inscription** simplifié
- **Suivi des paiements** et chèques
- **Encaissement automatisé** avec dates
- **Statistiques financières** en temps réel
- **Remises familiales** automatiques (10%)

### 🤝 Gestion des Bénévoles
- **Profils complets** avec coordonnées
- **Attribution des sections** référées
- **Composition des bureaux** par saison
- **Historique des fonctions**

### 🏢 Gestion des Sections
- **Organisation hiérarchique** des sections
- **Référents et contacts** intégrés
- **Statistiques par section** (activités, inscrits)
- **Descriptions et objectifs**

### 📅 Gestion des Saisons
- **Périodes d'activité** configurables
- **Statuts automatiques** (à venir, en cours, terminée)
- **Calcul de durées** et statistiques

## 🚀 Installation Rapide

### Prérequis
- **XAMPP** (Apache + MySQL + PHP 7.4+)
- **Navigateur moderne** (Chrome, Firefox, Safari, Edge)

### Installation en 3 étapes

1. **Démarrer XAMPP**
   ```bash
   # Lancer Apache et MySQL depuis le panneau XAMPP
   ```

2. **Installer la base de données**
   - Ouvrir phpMyAdmin (`http://localhost/phpmyadmin`)
   - Copier-coller le contenu de `install_complete.sql`
   - Cliquer "Exécuter"

3. **Accéder à l'application**
   ```
   http://localhost/bdd/Projet_BDD/
   ```

## 📁 Structure du Projet

```
Projet_BDD/
├── 🎨 assets/css/style.css     # Styles modernes avec animations
├── ⚙️ config.php               # Configuration base de données
├── 🗄️ database.sql            # Structure des tables
├── 📊 install_complete.sql     # Installation complète avec données
├── 🏠 index.php               # Page d'accueil avec navigation
├── 📊 dashboard.php           # Tableau de bord avec graphiques
├── 👥 adherents.php           # Gestion des adhérents
├── 🏃 activites.php           # Catalogue des activités
├── 📋 inscriptions.php        # Suivi des inscriptions
├── 🤝 benevoles.php          # Administration des bénévoles
├── 🏢 sections.php           # Organisation des sections
├── 📅 saisons.php            # Gestion des périodes
└── 📖 README.md              # Documentation complète
```

## 🛠️ Technologies Utilisées

### Backend
- **PHP 7.4+** avec PDO pour la sécurité
- **MySQL 8.0** avec contraintes d'intégrité
- **Architecture MVC** simplifiée

### Frontend
- **HTML5 sémantique** et accessible
- **CSS3 avancé** (Grid, Flexbox, animations)
- **JavaScript ES6** pour l'interactivité
- **Chart.js** pour les graphiques
- **Font Awesome 6** pour les icônes

### Sécurité
- **Requêtes préparées** PDO anti-injection SQL
- **Validation côté serveur** et client
- **Échappement HTML** automatique
- **Contraintes de base de données**

## 📱 Responsive Design

- **Mobile First** : optimisé pour smartphones
- **Tablettes** : interface adaptée aux écrans moyens
- **Desktop** : expérience complète grand écran
- **Grilles flexibles** qui s'adaptent automatiquement

## 🎯 Fonctionnalités Avancées

### Tableau de Bord Interactif
- Graphique en secteurs des inscriptions par section
- Statistiques financières en temps réel
- Top 5 des activités les plus populaires
- Historique des inscriptions récentes

### Gestion Financière
- Suivi des encaissements de chèques
- Calcul automatique des remises familiales
- Statistiques de recettes par période
- États des paiements en attente

### Interface Utilisateur
- Animations d'entrée progressives
- Messages de feedback visuels
- Confirmations de suppression
- Chargement asynchrone des données

## 🧪 Données de Test Incluses

- **8 adhérents** avec profils complets
- **5 sections** (Football, Tennis, Natation, Danse, Yoga)
- **10 activités** avec tarifs variés
- **5 bénévoles** référents de sections
- **10 inscriptions** avec différents statuts
- **2 saisons** (2024 et 2025)

## 🎨 Aperçu Visuel

### Page d'Accueil
- Navigation par cartes colorées avec icônes
- Design glassmorphism avec effets de transparence
- Animations au survol et transitions fluides

### Tableau de Bord
- Cartes statistiques avec dégradés colorés
- Graphique interactif Chart.js
- Mise en page responsive en grille

### Pages de Gestion
- Formulaires modernes avec validation visuelle
- Tableaux stylisés avec tri et recherche
- Boutons d'action avec confirmations

## 🔧 Configuration

### Base de Données (config.php)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ascg_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Personnalisation CSS
- Variables CSS pour couleurs et espacements
- Classes utilitaires pour composants
- Breakpoints responsive configurables

## 📈 Évolutions Possibles

- **API REST** pour applications mobiles
- **Système de notifications** par email
- **Génération de rapports** PDF
- **Paiements en ligne** intégrés
- **Planning des activités** avec calendrier
- **Gestion des présences** et absences

## 👨‍💻 Développement

**Projet académique** réalisé dans le cadre du cours :
- **Matière** : Bases de Données (M. Delhom)
- **Niveau** : L3 Informatique
- **Année** : 2025/2026
- **Objectif** : Conception et réalisation d'une application web complète

---
