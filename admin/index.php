
<?php
// Inclut le fichier de connexion à la base de données
require_once '../config/db.php';

// Inclut le fichier qui gère les sessions
require_once '../config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once '../includes/fonction.php';

// Oblige l'utilisateur à être administrateur
requireAdmin();

// Récupère les statistiques principales de l'administration
$stats = $pdo->query('
    SELECT
        (SELECT COUNT(*) FROM utilisateurs WHERE role = "user") AS nb_users,
        (SELECT COUNT(*) FROM questions) AS nb_questions,
        (SELECT COUNT(*) FROM tentatives WHERE DATE(date_debut) = CURDATE()) AS parties_today,
        (SELECT ROUND(AVG(note_sur_20),1) FROM tentatives WHERE invalide = 0) AS taux_moyen
')->fetch();

// Récupère les 10 dernières parties jouées
$stmt = $pdo->query('
    SELECT t.*, u.prenom, u.nom, c.nom AS cat_nom
    FROM tentatives t
    JOIN utilisateurs u ON t.utilisateur_id = u.id
    JOIN categories c ON t.categorie_id = c.id
    ORDER BY t.date_debut DESC LIMIT 10
');

// Stocke les dernières parties
$derniers = $stmt->fetchAll();

// Récupère les 5 dernières tentatives invalides
$invalides = $pdo->query('
    SELECT t.*, u.prenom, u.nom, c.nom AS cat_nom
    FROM tentatives t
    JOIN utilisateurs u ON t.utilisateur_id = u.id
    JOIN categories c ON t.categorie_id = c.id
    WHERE t.invalide = 1
    ORDER BY t.date_debut DESC LIMIT 5
')->fetchAll();

// Définit le titre de la page
$page_title = 'Dashboard Admin';

// Inclut l'en-tête du site
include '../includes/header.php';
?>

<div class="admin-page">
    <!-- En-tête de la page administrateur -->
    <div class="admin-header">
        <!-- Titre principal -->
        <h1>⚙️ Dashboard Admin</h1>

        <!-- Navigation de l'espace administrateur -->
        <div class="admin-nav">
            <a href="/admin/index.php" class="admin-nav-link active">Dashboard</a>
            <a href="/admin/utilisateurs.php" class="admin-nav-link">Utilisateurs</a>
            <a href="/admin/questions.php" class="admin-nav-link">Questions</a>
            <a href="/admin/categories.php" class="admin-nav-link">Catégories</a>
            <a href="/admin/stats.php" class="admin-nav-link">Statistiques</a>
        </div>
    </div>

    <!-- Cartes des statistiques principales -->
    <div class="admin-stats">
        <!-- Nombre d'utilisateurs -->
        <div class="admin-stat-card">
            <div class="asc-icon">👥</div>
            <div class="asc-value"><?= $stats['nb_users'] ?></div>
            <div class="asc-label">Utilisateurs</div>
        </div>

        <!-- Nombre de questions -->
        <div class="admin-stat-card">
            <div class="asc-icon">❓</div>
            <div class="asc-value"><?= $stats['nb_questions'] ?></div>
            <div class="asc-label">Questions</div>
        </div>

        <!-- Nombre de parties jouées aujourd'hui -->
        <div class="admin-stat-card">
            <div class="asc-icon">🎮</div>
            <div class="asc-value"><?= $stats['parties_today'] ?></div>
            <div class="asc-label">Parties aujourd'hui</div>
        </div>

        <!-- Moyenne globale des notes -->
        <div class="admin-stat-card">
            <div class="asc-icon">📊</div>
            <div class="asc-value"><?= $stats['taux_moyen'] ?? 0 ?>/20</div>
            <div class="asc-label">Moyenne globale</div>
        </div>
    </div>

    <!-- Grille contenant les tableaux d'administration -->
    <div class="admin-grid">
        <!-- Section des dernières parties -->
        <div class="admin-section">
            <h2>Dernières parties</h2>

            <!-- Tableau des dernières parties -->
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Joueur</th>
                        <th>Catégorie</th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Parcourt les dernières parties -->
                    <?php foreach ($derniers as $t): ?>
                    <tr>
                        <!-- Affiche le nom complet du joueur -->
                        <td><?= htmlspecialchars($t['prenom'] . ' ' . $t['nom']) ?></td>

                        <!-- Affiche la catégorie -->
                        <td><?= htmlspecialchars($t['cat_nom']) ?></td>

                        <!-- Affiche la note -->
                        <td><strong><?= $t['note_sur_20'] ?>/20</strong></td>

                        <!-- Affiche la date formatée -->
                        <td><?= formaterDate($t['date_debut']) ?></td>

                        <!-- Affiche le statut valide ou invalide -->
                        <td><?= $t['invalide'] ? '<span class="badge mention-insuffisant">Invalide</span>' : '<span class="badge mention-excellent">Valide</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Section des tentatives invalides -->
        <div class="admin-section">
            <h2>⚠️ Tentatives invalides récentes</h2>

            <!-- Si aucune tentative invalide n'existe -->
            <?php if (empty($invalides)): ?>
                <p class="empty-text">Aucune tentative invalide.</p>
            <?php else: ?>
            <!-- Tableau des tentatives invalides -->
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Joueur</th>
                        <th>Catégorie</th>
                        <th>Note</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Parcourt les tentatives invalides -->
                    <?php foreach ($invalides as $t): ?>
                    <tr class="row-invalid">
                        <!-- Affiche le nom complet du joueur -->
                        <td><?= htmlspecialchars($t['prenom'] . ' ' . $t['nom']) ?></td>

                        <!-- Affiche la catégorie -->
                        <td><?= htmlspecialchars($t['cat_nom']) ?></td>

                        <!-- Affiche la note -->
                        <td><?= $t['note_sur_20'] ?>/20</td>

                        <!-- Affiche la date formatée -->
                        <td><?= formaterDate($t['date_debut']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php

include '../includes/footer.php';
?>
```