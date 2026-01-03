<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_POST && $_POST['action'] == 'ajouter') {
    try {
        $stmt = $pdo->prepare("INSERT INTO ADHERENT (nom_adh, prenom_adh, date_naissance, adresse_adh, code_postal, ville_adh, telephone_adh, email_adh) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['adresse'], $_POST['code_postal'], $_POST['ville'], $_POST['telephone'], $_POST['email']]);
        $message = "Adhérent ajouté avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de l'ajout : " . $e->getMessage();
        $messageType = 'danger';
    }
}

if ($_POST && $_POST['action'] == 'supprimer') {
    try {
        $stmt = $pdo->prepare("DELETE FROM ADHERENT WHERE num_adherent = ?");
        $stmt->execute([$_POST['id']]);
        $message = "Adhérent supprimé avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
        $messageType = 'danger';
    }
}

$adherents = $pdo->query("SELECT * FROM ADHERENT ORDER BY nom_adh")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhérents - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-user-friends" style="color: #e74c3c; margin-right: 15px;"></i>Gestion des Adhérents</h1>
            <p>Ajouter et gérer les membres de l'association</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> fade-in">
                <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container fade-in">
            <h3><i class="fas fa-user-plus"></i> Nouvel Adhérent</h3>
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
                        <label for="date_naissance">Date de naissance *</label>
                        <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="code_postal">Code postal *</label>
                        <input type="text" id="code_postal" name="code_postal" class="form-control" pattern="[0-9]{5}" required>
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville *</label>
                        <input type="text" id="ville" name="ville" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter l'adhérent
                </button>
            </form>
        </div>

        <div class="table-container fade-in">
            <h3><i class="fas fa-list"></i> Liste des Adhérents (<?= count($adherents) ?>)</h3>
            <?php if (count($adherents) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date naissance</th>
                            <th>Ville</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adherents as $a): ?>
                        <tr>
                            <td><strong><?= $a['num_adherent'] ?></strong></td>
                            <td><?= htmlspecialchars($a['nom_adh']) ?></td>
                            <td><?= htmlspecialchars($a['prenom_adh']) ?></td>
                            <td><?= date('d/m/Y', strtotime($a['date_naissance'])) ?></td>
                            <td><?= htmlspecialchars($a['ville_adh']) ?></td>
                            <td><?= htmlspecialchars($a['telephone_adh']) ?></td>
                            <td><?= htmlspecialchars($a['email_adh']) ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet adhérent ?')">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="id" value="<?= $a['num_adherent'] ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #7f8c8d; margin-top: 30px;">
                    <i class="fas fa-info-circle"></i> Aucun adhérent enregistré
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