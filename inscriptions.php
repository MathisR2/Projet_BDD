<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_POST && $_POST['action'] == 'ajouter') {
    try {
        $stmt = $pdo->prepare("INSERT INTO INSCRIPTION (num_adherent, code_activite, annee_saison, date_inscription, montant_paye, numero_cheque) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['num_adherent'], $_POST['code_activite'], $_POST['annee_saison'], $_POST['date_inscription'], $_POST['montant_paye'], $_POST['numero_cheque']]);
        $message = "Inscription enregistrée avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de l'inscription : " . $e->getMessage();
        $messageType = 'danger';
    }
}

if ($_POST && $_POST['action'] == 'encaisser') {
    try {
        $stmt = $pdo->prepare("UPDATE INSCRIPTION SET date_encaissement = ? WHERE num_adherent = ? AND code_activite = ? AND annee_saison = ?");
        $stmt->execute([date('Y-m-d'), $_POST['num_adherent'], $_POST['code_activite'], $_POST['annee_saison']]);
        $message = "Chèque encaissé avec succès !";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de l'encaissement : " . $e->getMessage();
        $messageType = 'danger';
    }
}

$inscriptions = $pdo->query("
    SELECT i.*, a.nom_adh, a.prenom_adh, ac.nom_activite, ac.tarif_activite, s.nom_section 
    FROM INSCRIPTION i 
    JOIN ADHERENT a ON i.num_adherent = a.num_adherent 
    JOIN ACTIVITE ac ON i.code_activite = ac.code_activite 
    JOIN SECTION s ON ac.code_section = s.code_section
    ORDER BY i.date_inscription DESC
")->fetchAll();

$adherents = $pdo->query("SELECT * FROM ADHERENT ORDER BY nom_adh")->fetchAll();
$activites = $pdo->query("SELECT a.*, s.nom_section FROM ACTIVITE a JOIN SECTION s ON a.code_section = s.code_section ORDER BY a.nom_activite")->fetchAll();
$saisons = $pdo->query("SELECT * FROM SAISON ORDER BY annee_saison DESC")->fetchAll();

// Statistiques
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total_inscriptions,
        SUM(montant_paye) as total_montant,
        COUNT(CASE WHEN date_encaissement IS NULL THEN 1 END) as cheques_non_encaisses
    FROM INSCRIPTION
")->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions - ASCG</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <a href="index.php" style="float: left; color: #667eea; text-decoration: none; font-size: 1.2rem;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-clipboard-list" style="color: #9b59b6; margin-right: 15px;"></i>Gestion des Inscriptions</h1>
            <p>Suivi des inscriptions et encaissement des chèques</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> fade-in">
                <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;" class="fade-in">
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h3 style="margin: 0; font-size: 2rem;"><?= $stats['total_inscriptions'] ?></h3>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Inscriptions</p>
            </div>
            <div style="background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <i class="fas fa-euro-sign" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h3 style="margin: 0; font-size: 2rem;"><?= number_format($stats['total_montant'], 0) ?>€</h3>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Recettes</p>
            </div>
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <i class="fas fa-clock" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h3 style="margin: 0; font-size: 2rem;"><?= $stats['cheques_non_encaisses'] ?></h3>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Chèques à encaisser</p>
            </div>
        </div>

        <div class="form-container fade-in">
            <h3><i class="fas fa-user-plus"></i> Nouvelle Inscription</h3>
            <form method="POST" id="inscriptionForm">
                <input type="hidden" name="action" value="ajouter">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="num_adherent">Adhérent *</label>
                        <select id="num_adherent" name="num_adherent" class="form-control" required>
                            <option value="">Choisir un adhérent</option>
                            <?php foreach ($adherents as $a): ?>
                            <option value="<?= $a['num_adherent'] ?>"><?= htmlspecialchars($a['nom_adh'] . ' ' . $a['prenom_adh']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="code_activite">Activité *</label>
                        <select id="code_activite" name="code_activite" class="form-control" required onchange="updateTarif()">
                            <option value="">Choisir une activité</option>
                            <?php foreach ($activites as $ac): ?>
                            <option value="<?= $ac['code_activite'] ?>" data-tarif="<?= $ac['tarif_activite'] ?>">
                                <?= htmlspecialchars($ac['nom_activite']) ?> - <?= htmlspecialchars($ac['nom_section']) ?> (<?= number_format($ac['tarif_activite'], 2) ?>€)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="annee_saison">Saison *</label>
                        <select id="annee_saison" name="annee_saison" class="form-control" required>
                            <option value="">Choisir une saison</option>
                            <?php foreach ($saisons as $s): ?>
                            <option value="<?= $s['annee_saison'] ?>" <?= $s === reset($saisons) ? 'selected' : '' ?>>
                                <?= $s['annee_saison'] ?> (<?= date('d/m/Y', strtotime($s['date_debut'])) ?> - <?= date('d/m/Y', strtotime($s['date_fin'])) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_inscription">Date d'inscription *</label>
                        <input type="date" id="date_inscription" name="date_inscription" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="montant_paye">Montant payé (€) *</label>
                        <input type="number" id="montant_paye" name="montant_paye" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="numero_cheque">Numéro de chèque</label>
                        <input type="text" id="numero_cheque" name="numero_cheque" class="form-control" placeholder="Optionnel">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Enregistrer l'inscription
                </button>
            </form>
        </div>

        <div class="table-container fade-in">
            <h3><i class="fas fa-list"></i> Liste des Inscriptions (<?= count($inscriptions) ?>)</h3>
            <?php if (count($inscriptions) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Adhérent</th>
                            <th>Activité</th>
                            <th>Section</th>
                            <th>Saison</th>
                            <th>Date inscription</th>
                            <th>Montant</th>
                            <th>N° Chèque</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscriptions as $i): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($i['nom_adh'] . ' ' . $i['prenom_adh']) ?></strong></td>
                            <td><?= htmlspecialchars($i['nom_activite']) ?></td>
                            <td><?= htmlspecialchars($i['nom_section']) ?></td>
                            <td><?= $i['annee_saison'] ?></td>
                            <td><?= date('d/m/Y', strtotime($i['date_inscription'])) ?></td>
                            <td><strong><?= number_format($i['montant_paye'], 2) ?>€</strong></td>
                            <td><?= htmlspecialchars($i['numero_cheque']) ?: '-' ?></td>
                            <td>
                                <?php if ($i['date_encaissement']): ?>
                                    <span style="background: #2ecc71; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                        <i class="fas fa-check"></i> Encaissé le <?= date('d/m/Y', strtotime($i['date_encaissement'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="background: #e74c3c; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                        <i class="fas fa-clock"></i> En attente
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$i['date_encaissement']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="encaisser">
                                    <input type="hidden" name="num_adherent" value="<?= $i['num_adherent'] ?>">
                                    <input type="hidden" name="code_activite" value="<?= $i['code_activite'] ?>">
                                    <input type="hidden" name="annee_saison" value="<?= $i['annee_saison'] ?>">
                                    <button type="submit" class="btn btn-success" style="padding: 5px 10px; font-size: 0.8rem;" title="Encaisser le chèque">
                                        <i class="fas fa-money-check"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #7f8c8d; margin-top: 30px;">
                    <i class="fas fa-info-circle"></i> Aucune inscription enregistrée
                </p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function updateTarif() {
            const select = document.getElementById('code_activite');
            const montantInput = document.getElementById('montant_paye');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.dataset.tarif) {
                montantInput.value = selectedOption.dataset.tarif;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>