<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_POST && $_POST['action'] == 'ajouter') {
    try {
        $stmt = $pdo->prepare("INSERT INTO SECTION (code_section, nom_section, description_section, num_referent) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['code_section'], $_POST['nom_section'], $_POST['description'], $_POST['num_referent']]);
        $message = "Section ajoutée avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        $messageType = 'danger';
    }
}

if ($_POST && $_POST['action'] == 'supprimer') {
    try {
        $stmt = $pdo->prepare("DELETE FROM SECTION WHERE code_section = ?");
        $stmt->execute([$_POST['code']]);
        $message = "Section supprimée avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        $messageType = 'danger';
    }
}

$sections = $pdo->query("
    SELECT s.*, b.nom_ben, b.prenom_ben, b.telephone_ben, b.email_ben,
           COUNT(DISTINCT a.code_activite) as nb_activites,
           COUNT(DISTINCT i.num_adherent) as nb_inscrits
    FROM SECTION s 
    JOIN BENEVOLE b ON s.num_referent = b.num_benevole
    LEFT JOIN ACTIVITE a ON s.code_section = a.code_section
    LEFT JOIN INSCRIPTION i ON a.code_activite = i.code_activite AND i.annee_saison = 2024
    GROUP BY s.code_section
    ORDER BY s.nom_section
")->fetchAll();

$benevoles = $pdo->query("SELECT * FROM BENEVOLE ORDER BY nom_ben")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-sitemap" style="color: #2ecc71; margin-right: 15px;"></i>Gestion des Sections</h1>
            <p>Organisation des sections et leurs référents</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> fade-in">
                <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container fade-in">
            <h3><i class="fas fa-plus-circle"></i> Nouvelle Section</h3>
            <form method="POST">
                <input type="hidden" name="action" value="ajouter">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="code_section">Code section *</label>
                        <input type="text" id="code_section" name="code_section" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nom_section">Nom de la section *</label>
                        <input type="text" id="nom_section" name="nom_section" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="num_referent">Référent *</label>
                        <select id="num_referent" name="num_referent" class="form-control" required>
                            <option value="">Choisir un référent</option>
                            <?php foreach ($benevoles as $b): ?>
                            <option value="<?= $b['num_benevole'] ?>"><?= htmlspecialchars($b['nom_ben'] . ' ' . $b['prenom_ben']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Description de la section..."></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter la section
                </button>
            </form>
        </div>

        <div class="table-container fade-in">
            <h3><i class="fas fa-list"></i> Liste des Sections (<?= count($sections) ?>)</h3>
            <?php if (count($sections) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px; margin-top: 20px;">
                    <?php foreach ($sections as $s): ?>
                    <div style="background: #f8f9fa; border-radius: 15px; padding: 25px; border-left: 4px solid #2ecc71; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div>
                                <h4 style="color: #2c3e50; margin: 0 0 5px 0; font-size: 1.3rem;">
                                    <i class="fas fa-tag" style="color: #2ecc71; margin-right: 8px;"></i>
                                    <?= htmlspecialchars($s['nom_section']) ?>
                                </h4>
                                <p style="color: #7f8c8d; margin: 0; font-size: 0.9rem;">
                                    <i class="fas fa-code" style="margin-right: 5px;"></i>
                                    Code: <strong><?= htmlspecialchars($s['code_section']) ?></strong>
                                </p>
                            </div>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cette section ?')">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="code" value="<?= $s['code_section'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 5px 8px; font-size: 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <?php if ($s['description_section']): ?>
                        <div style="background: white; border-radius: 8px; padding: 15px; margin: 15px 0;">
                            <p style="margin: 0; color: #555; font-size: 0.9rem; line-height: 1.4;">
                                <i class="fas fa-info-circle" style="color: #3498db; margin-right: 5px;"></i>
                                <?= htmlspecialchars($s['description_section']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Référent -->
                        <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; border-radius: 10px; padding: 15px; margin: 15px 0;">
                            <h5 style="margin: 0 0 8px 0; font-size: 0.9rem; opacity: 0.9;">
                                <i class="fas fa-user-tie" style="margin-right: 5px;"></i> Référent
                            </h5>
                            <p style="margin: 0 0 5px 0; font-weight: 600;">
                                <?= htmlspecialchars($s['nom_ben'] . ' ' . $s['prenom_ben']) ?>
                            </p>
                            <p style="margin: 0; font-size: 0.8rem; opacity: 0.9;">
                                <i class="fas fa-phone" style="margin-right: 5px;"></i>
                                <?= htmlspecialchars($s['telephone_ben']) ?>
                            </p>
                            <p style="margin: 0; font-size: 0.8rem; opacity: 0.9;">
                                <i class="fas fa-envelope" style="margin-right: 5px;"></i>
                                <?= htmlspecialchars($s['email_ben']) ?>
                            </p>
                        </div>
                        
                        <!-- Statistiques -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                            <div style="background: #2ecc71; color: white; padding: 12px; border-radius: 8px; text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: bold;"><?= $s['nb_activites'] ?></div>
                                <div style="font-size: 0.8rem; opacity: 0.9;">Activités</div>
                            </div>
                            <div style="background: #f39c12; color: white; padding: 12px; border-radius: 8px; text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: bold;"><?= $s['nb_inscrits'] ?></div>
                                <div style="font-size: 0.8rem; opacity: 0.9;">Inscrits 2024</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #7f8c8d; margin-top: 30px;">
                    <i class="fas fa-info-circle"></i> Aucune section enregistrée
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