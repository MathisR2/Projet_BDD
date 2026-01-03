<?php
require_once 'config.php';

// Statistiques générales
$stats = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM ADHERENT) as total_adherents,
        (SELECT COUNT(*) FROM ACTIVITE) as total_activites,
        (SELECT COUNT(*) FROM SECTION) as total_sections,
        (SELECT COUNT(*) FROM INSCRIPTION WHERE annee_saison = 2024) as inscriptions_2024,
        (SELECT SUM(montant_paye) FROM INSCRIPTION WHERE annee_saison = 2024) as recettes_2024,
        (SELECT COUNT(*) FROM INSCRIPTION WHERE date_encaissement IS NULL) as cheques_attente
")->fetch();

// Activités les plus populaires
$activites_populaires = $pdo->query("
    SELECT a.nom_activite, s.nom_section, COUNT(i.num_adherent) as nb_inscrits, a.tarif_activite
    FROM ACTIVITE a
    LEFT JOIN INSCRIPTION i ON a.code_activite = i.code_activite AND i.annee_saison = 2024
    JOIN SECTION s ON a.code_section = s.code_section
    GROUP BY a.code_activite
    ORDER BY nb_inscrits DESC
    LIMIT 5
")->fetchAll();

// Répartition par section
$repartition_sections = $pdo->query("
    SELECT s.nom_section, COUNT(i.num_adherent) as nb_inscrits, SUM(i.montant_paye) as recettes
    FROM SECTION s
    LEFT JOIN ACTIVITE a ON s.code_section = a.code_section
    LEFT JOIN INSCRIPTION i ON a.code_activite = i.code_activite AND i.annee_saison = 2024
    GROUP BY s.code_section
    ORDER BY nb_inscrits DESC
")->fetchAll();

// Inscriptions récentes
$inscriptions_recentes = $pdo->query("
    SELECT i.*, a.nom_adh, a.prenom_adh, ac.nom_activite, s.nom_section
    FROM INSCRIPTION i
    JOIN ADHERENT a ON i.num_adherent = a.num_adherent
    JOIN ACTIVITE ac ON i.code_activite = ac.code_activite
    JOIN SECTION s ON ac.code_section = s.code_section
    WHERE i.annee_saison = 2024
    ORDER BY i.date_inscription DESC
    LIMIT 10
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-chart-line" style="color: #1abc9c; margin-right: 15px;"></i>Tableau de Bord</h1>
            <p>Vue d'ensemble de l'activité de l'association</p>
        </div>

        <!-- Statistiques principales -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;" class="fade-in">
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 25px; border-radius: 15px; text-align: center;">
                <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="margin: 0; font-size: 2.5rem;"><?= $stats['total_adherents'] ?></h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Adhérents</p>
            </div>
            <div style="background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 25px; border-radius: 15px; text-align: center;">
                <i class="fas fa-running" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="margin: 0; font-size: 2.5rem;"><?= $stats['total_activites'] ?></h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Activités</p>
            </div>
            <div style="background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; padding: 25px; border-radius: 15px; text-align: center;">
                <i class="fas fa-clipboard-list" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="margin: 0; font-size: 2.5rem;"><?= $stats['inscriptions_2024'] ?></h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Inscriptions 2024</p>
            </div>
            <div style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 25px; border-radius: 15px; text-align: center;">
                <i class="fas fa-euro-sign" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="margin: 0; font-size: 2.5rem;"><?= number_format($stats['recettes_2024'], 0) ?>€</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Recettes 2024</p>
            </div>
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 25px; border-radius: 15px; text-align: center;">
                <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="margin: 0; font-size: 2.5rem;"><?= $stats['cheques_attente'] ?></h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Chèques en attente</p>
            </div>
            <div style="background: linear-gradient(135deg, #1abc9c, #16a085); color: white; padding: 25px; border-radius: 15px; text-align: center;">
                <i class="fas fa-sitemap" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="margin: 0; font-size: 2.5rem;"><?= $stats['total_sections'] ?></h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Sections</p>
            </div>
        </div>

        <div class="dashboard-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Graphique des sections -->
            <div class="table-container fade-in">
                <h3><i class="fas fa-chart-pie"></i> Répartition par Section</h3>
                <canvas id="sectionsChart" width="400" height="300"></canvas>
            </div>

            <!-- Top activités -->
            <div class="table-container fade-in">
                <h3><i class="fas fa-trophy"></i> Activités les Plus Populaires</h3>
                <?php foreach ($activites_populaires as $index => $activite): ?>
                <div style="display: flex; align-items: center; padding: 15px; margin: 10px 0; background: #f8f9fa; border-radius: 8px; border-left: 4px solid <?= ['#e74c3c', '#f39c12', '#2ecc71', '#3498db', '#9b59b6'][$index] ?>;">
                    <div style="background: <?= ['#e74c3c', '#f39c12', '#2ecc71', '#3498db', '#9b59b6'][$index] ?>; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">
                        <?= $index + 1 ?>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;"><?= htmlspecialchars($activite['nom_activite']) ?></h4>
                        <p style="margin: 0; color: #7f8c8d; font-size: 0.9rem;"><?= htmlspecialchars($activite['nom_section']) ?></p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #2c3e50;"><?= $activite['nb_inscrits'] ?></div>
                        <div style="font-size: 0.8rem; color: #7f8c8d;">inscrits</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Inscriptions récentes -->
        <div class="table-container fade-in">
            <h3><i class="fas fa-history"></i> Inscriptions Récentes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Adhérent</th>
                        <th>Activité</th>
                        <th>Section</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscriptions_recentes as $inscription): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($inscription['date_inscription'])) ?></td>
                        <td><strong><?= htmlspecialchars($inscription['nom_adh'] . ' ' . $inscription['prenom_adh']) ?></strong></td>
                        <td><?= htmlspecialchars($inscription['nom_activite']) ?></td>
                        <td><?= htmlspecialchars($inscription['nom_section']) ?></td>
                        <td><strong><?= number_format($inscription['montant_paye'], 2) ?>€</strong></td>
                        <td>
                            <?php if ($inscription['date_encaissement']): ?>
                                <span style="background: #2ecc71; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    <i class="fas fa-check"></i> Encaissé
                                </span>
                            <?php else: ?>
                                <span style="background: #e74c3c; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    <i class="fas fa-clock"></i> En attente
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Graphique des sections
        const ctx = document.getElementById('sectionsChart').getContext('2d');
        const sectionsData = {
            labels: [<?php echo implode(',', array_map(function($s) { return '"' . addslashes($s['nom_section']) . '"'; }, $repartition_sections)); ?>],
            datasets: [{
                data: [<?php echo implode(',', array_map(function($s) { return $s['nb_inscrits']; }, $repartition_sections)); ?>],
                backgroundColor: [
                    '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c'
                ],
                borderWidth: 0
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: sectionsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>