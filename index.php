<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASCG - Association Sportive et Culturelle de Grenoble</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <h1><i class="fas fa-users" style="color: #667eea; margin-right: 15px;"></i>ASCG</h1>
            <p>Association Sportive et Culturelle de Grenoble</p>
            <p style="font-size: 0.9rem; margin-top: 10px; opacity: 0.8;">Système de gestion des adhérents et activités</p>
        </div>
        
        <div class="nav-grid fade-in">
            <a href="dashboard.php" class="nav-card">
                <span class="icon"><i class="fas fa-chart-line"></i></span>
                <h3>Tableau de Bord</h3>
                <p>Vue d'ensemble et statistiques de l'association avec graphiques</p>
            </a>
            
            <a href="adherents.php" class="nav-card">
                <span class="icon"><i class="fas fa-user-friends"></i></span>
                <h3>Adhérents</h3>
                <p>Gestion des membres de l'association, inscriptions et informations personnelles</p>
            </a>
            
            <a href="benevoles.php" class="nav-card">
                <span class="icon"><i class="fas fa-hands-helping"></i></span>
                <h3>Bénévoles</h3>
                <p>Administration des bénévoles et composition des bureaux de sections</p>
            </a>
            
            <a href="sections.php" class="nav-card">
                <span class="icon"><i class="fas fa-sitemap"></i></span>
                <h3>Sections</h3>
                <p>Organisation des différentes sections de l'association et leurs référents</p>
            </a>
            
            <a href="activites.php" class="nav-card">
                <span class="icon"><i class="fas fa-running"></i></span>
                <h3>Activités</h3>
                <p>Catalogue des activités proposées par chaque section avec tarifs</p>
            </a>
            
            <a href="inscriptions.php" class="nav-card">
                <span class="icon"><i class="fas fa-clipboard-list"></i></span>
                <h3>Inscriptions</h3>
                <p>Suivi des inscriptions, paiements et gestion des chèques</p>
            </a>
            
            <a href="saisons.php" class="nav-card">
                <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                <h3>Saisons</h3>
                <p>Configuration des périodes d'activité et gestion annuelle</p>
            </a>
        </div>
    </div>
    
    <script>
        // Animation d'entrée progressive
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.nav-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>