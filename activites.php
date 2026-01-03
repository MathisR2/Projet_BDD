<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_POST && $_POST['action'] == 'ajouter') {
    try {
        $stmt = $pdo->prepare("INSERT INTO ACTIVITE (code_activite, nom_activite, description_activite, tarif_activite, code_section) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['code_activite'], $_POST['nom_activite'], $_POST['description'], $_POST['tarif'], $_POST['code_section']]);
        $message = "Activité ajoutée avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de l'ajout : " . $e->getMessage();
        $messageType = 'danger';
    }
}

if ($_POST && $_POST['action'] == 'supprimer') {
    try {
        $stmt = $pdo->prepare("DELETE FROM ACTIVITE WHERE code_activite = ?");
        $stmt->execute([$_POST['code']]);
        $message = "Activité supprimée avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
        $messageType = 'danger';
    }
}

$activites = $pdo->query("SELECT a.*, s.nom_section FROM ACTIVITE a JOIN SECTION s ON a.code_section = s.code_section ORDER BY a.nom_activite")->fetchAll();
$sections = $pdo->query("SELECT * FROM SECTION ORDER BY nom_section")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activités - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-running" style="color: #f39c12; margin-right: 15px;"></i>Gestion des Activités</h1>
            <p>Catalogue des activités proposées par l'association</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> fade-in">
                <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container fade-in">
            <h3><i class="fas fa-plus-circle"></i> Nouvelle Activité</h3>
            <form method="POST">
                <input type="hidden" name="action" value="ajouter">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="code_activite">Code activité *</label>
                        <input type="text" id="code_activite" name="code_activite" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nom_activite">Nom de l'activité *</label>
                        <input type="text" id="nom_activite" name="nom_activite" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="code_section">Section *</label>
                        <select id="code_section" name="code_section" class="form-control" required>
                            <option value="">Choisir une section</option>
                            <?php foreach ($sections as $s): ?>
                            <option value="<?= $s['code_section'] ?>"><?= htmlspecialchars($s['nom_section']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tarif">Tarif (€) *</label>
                        <input type="number" id="tarif" name="tarif" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Description de l'activité..."></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter l'activité
                </button>
            </form>
        </div>

        <div class="table-container fade-in">
            <h3><i class="fas fa-list"></i> Catalogue des Activités (<?= count($activites) ?>)</h3>
            <?php if (count($activites) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-top: 20px;">
                    <?php foreach ($activites as $a): ?>
                    <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; border-left: 4px solid #f39c12;">
                        <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 10px;">
                            <div style="flex: 1;">
                                <h4 style="color: #2c3e50; margin: 0 0 5px 0;">
                                    <i class="fas fa-tag" style="color: #f39c12; margin-right: 8px;"></i>
                                    <?= htmlspecialchars($a['nom_activite']) ?>
                                </h4>
                                <p style="color: #7f8c8d; margin: 0; font-size: 0.9rem;">
                                    <i class="fas fa-sitemap" style="margin-right: 5px;"></i>
                                    <?= htmlspecialchars($a['nom_section']) ?>
                                </p>
                            </div>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="code" value="<?= $a['code_activite'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 5px 8px; font-size: 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div style="background: white; border-radius: 5px; padding: 10px; margin: 10px 0;">
                            <p style="margin: 0; color: #2c3e50; font-size: 0.9rem;">
                                <strong>Code:</strong> <?= htmlspecialchars($a['code_activite']) ?>
                            </p>
                        </div>
                        
                        <?php if ($a['description_activite']): ?>
                        <p style="color: #555; margin: 10px 0; font-size: 0.9rem; line-height: 1.4;">
                            <i class="fas fa-info-circle" style="color: #3498db; margin-right: 5px;"></i>
                            <?= htmlspecialchars($a['description_activite']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <div style="text-align: right; margin-top: 15px;">
                            <span style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; padding: 8px 15px; border-radius: 20px; font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-euro-sign"></i> <?= number_format($a['tarif_activite'], 2) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #7f8c8d; margin-top: 30px;">
                    <i class="fas fa-info-circle"></i> Aucune activité enregistrée
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