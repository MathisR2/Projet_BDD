<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_POST && $_POST['action'] == 'ajouter') {
    try {
        $stmt = $pdo->prepare("INSERT INTO BENEVOLE (nom_ben, prenom_ben, telephone_ben, email_ben) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['email']]);
        $message = "Bénévole ajouté avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        $messageType = 'danger';
    }
}

if ($_POST && $_POST['action'] == 'supprimer') {
    try {
        $stmt = $pdo->prepare("DELETE FROM BENEVOLE WHERE num_benevole = ?");
        $stmt->execute([$_POST['id']]);
        $message = "Bénévole supprimé avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        $messageType = 'danger';
    }
}

$benevoles = $pdo->query("
    SELECT b.*, 
           COUNT(DISTINCT s.code_section) as nb_sections,
           GROUP_CONCAT(DISTINCT s.nom_section SEPARATOR ', ') as sections
    FROM BENEVOLE b
    LEFT JOIN SECTION s ON b.num_benevole = s.num_referent
    GROUP BY b.num_benevole
    ORDER BY b.nom_ben
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bénévoles - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-hands-helping" style="color: #3498db; margin-right: 15px;"></i>Gestion des Bénévoles</h1>
            <p>Administration des bénévoles et référents de sections</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> fade-in">
                <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container fade-in">
            <h3><i class="fas fa-user-plus"></i> Nouveau Bénévole</h3>
            <form method="POST">
                <input type="hidden" name="action" value="ajouter">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter le bénévole
                </button>
            </form>
        </div>

        <div class="table-container fade-in">
            <h3><i class="fas fa-list"></i> Liste des Bénévoles (<?= count($benevoles) ?>)</h3>
            <?php if (count($benevoles) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-top: 20px;">
                    <?php foreach ($benevoles as $b): ?>
                    <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; border-left: 4px solid #3498db;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div>
                                <h4 style="color: #2c3e50; margin: 0 0 5px 0;">
                                    <i class="fas fa-user" style="color: #3498db; margin-right: 8px;"></i>
                                    <?= htmlspecialchars($b['nom_ben'] . ' ' . $b['prenom_ben']) ?>
                                </h4>
                                <p style="color: #7f8c8d; margin: 0; font-size: 0.9rem;">
                                    <i class="fas fa-id-badge" style="margin-right: 5px;"></i>
                                    ID: <?= $b['num_benevole'] ?>
                                </p>
                            </div>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce bénévole ?')">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="id" value="<?= $b['num_benevole'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 5px 8px; font-size: 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div style="background: white; border-radius: 5px; padding: 15px; margin: 10px 0;">
                            <p style="margin: 0 0 8px 0; color: #2c3e50;">
                                <i class="fas fa-phone" style="color: #2ecc71; margin-right: 8px;"></i>
                                <strong><?= htmlspecialchars($b['telephone_ben']) ?></strong>
                            </p>
                            <p style="margin: 0; color: #2c3e50;">
                                <i class="fas fa-envelope" style="color: #e74c3c; margin-right: 8px;"></i>
                                <?= htmlspecialchars($b['email_ben']) ?>
                            </p>
                        </div>
                        
                        <?php if ($b['nb_sections'] > 0): ?>
                        <div style="margin-top: 15px;">
                            <p style="color: #7f8c8d; font-size: 0.9rem; margin: 0 0 5px 0;">
                                <i class="fas fa-sitemap" style="margin-right: 5px;"></i>
                                Référent de <?= $b['nb_sections'] ?> section(s):
                            </p>
                            <p style="color: #2c3e50; font-size: 0.9rem; margin: 0; font-weight: 500;">
                                <?= htmlspecialchars($b['sections']) ?>
                            </p>
                        </div>
                        <?php else: ?>
                        <div style="margin-top: 15px; text-align: center; color: #95a5a6; font-style: italic;">
                            <i class="fas fa-info-circle"></i> Aucune section assignée
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #7f8c8d; margin-top: 30px;">
                    <i class="fas fa-info-circle"></i> Aucun bénévole enregistré
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