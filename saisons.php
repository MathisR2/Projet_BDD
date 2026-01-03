<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_POST && $_POST['action'] == 'ajouter') {
    try {
        $stmt = $pdo->prepare("INSERT INTO SAISON (annee_saison, date_debut, date_fin) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['annee'], $_POST['date_debut'], $_POST['date_fin']]);
        $message = "Saison ajoutée avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        $messageType = 'danger';
    }
}

$saisons = $pdo->query("SELECT * FROM SAISON ORDER BY annee_saison DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisons - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-calendar-alt" style="color: #34495e; margin-right: 15px;"></i>Gestion des Saisons</h1>
            <p>Configuration des périodes d'activité annuelles</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> fade-in">
                <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container fade-in">
            <h3><i class="fas fa-plus-circle"></i> Nouvelle Saison</h3>
            <form method="POST">
                <input type="hidden" name="action" value="ajouter">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="annee">Année *</label>
                        <input type="number" id="annee" name="annee" class="form-control" min="2020" max="2030" required>
                    </div>
                    <div class="form-group">
                        <label for="date_debut">Date de début *</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="date_fin">Date de fin *</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter la saison
                </button>
            </form>
        </div>

        <div class="table-container fade-in">
            <h3><i class="fas fa-list"></i> Liste des Saisons (<?= count($saisons) ?>)</h3>
            <?php if (count($saisons) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Année</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Durée</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($saisons as $s): 
                            $debut = new DateTime($s['date_debut']);
                            $fin = new DateTime($s['date_fin']);
                            $duree = $debut->diff($fin)->days;
                            $maintenant = new DateTime();
                            $statut = $maintenant < $debut ? 'À venir' : ($maintenant > $fin ? 'Terminée' : 'En cours');
                            $couleur = $statut == 'En cours' ? '#2ecc71' : ($statut == 'À venir' ? '#f39c12' : '#95a5a6');
                        ?>
                        <tr>
                            <td><strong><?= $s['annee_saison'] ?></strong></td>
                            <td><?= date('d/m/Y', strtotime($s['date_debut'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($s['date_fin'])) ?></td>
                            <td><?= $duree ?> jours</td>
                            <td>
                                <span style="background: <?= $couleur ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    <?= $statut ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #7f8c8d; margin-top: 30px;">
                    <i class="fas fa-info-circle"></i> Aucune saison enregistrée
                </p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>