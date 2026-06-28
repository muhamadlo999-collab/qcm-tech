V
<?php
// Inclut le fichier de connexion à la base de données
require_once '../config/db.php';

// Inclut le fichier qui gère les sessions
require_once '../config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once '../includes/fonctions.php';

// Oblige l'utilisateur à être administrateur
requireAdmin();

// Récupère les statistiques de réussite par catégorie
$stats_cats = $pdo->query('
    SELECT c.nom, c.slug,
           COUNT(t.id) AS nb_parties,
           ROUND(AVG(t.note_sur_20), 1) AS moyenne,
           ROUND(AVG(t.nb_bonnes / c.nb_questions_qcm * 100), 1) AS taux_reussite
    FROM categories c
    LEFT JOIN tentatives t ON t.categorie_id = c.id AND t.invalide = 0
    GROUP BY c.id ORDER BY taux_reussite DESC
')->fetchAll();

// Récupère les questions avec le plus fort taux d'échec
$questions_dures = $pdo->query('
    SELECT q.question, c.nom AS cat_nom,
           COUNT(r.id) AS nb_reponses,
           SUM(CASE WHEN r.correcte = 0 THEN 1 ELSE 0 END) AS nb_faux,
           ROUND(SUM(CASE WHEN r.correcte = 0 THEN 1 ELSE 0 END) / COUNT(r.id) * 100, 1) AS taux_echec
    FROM reponses r
    JOIN questions q ON r.question_id = q.id
    JOIN categories c ON q.categorie_id = c.id
    GROUP BY r.question_id
    HAVING nb_reponses >= 3
    ORDER BY taux_echec DESC
    LIMIT 10
')->fetchAll();

// Récupère l'activité des 14 derniers jours
$activite = $pdo->query('
    SELECT DATE(date_debut) AS jour, COUNT(*) AS nb
    FROM tentatives
    WHERE date_debut >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    GROUP BY DATE(date_debut)
    ORDER BY jour ASC
')->fetchAll();

// Définit le titre de la page
$page_title = 'Statistiques';

// Inclut l'en-tête du site
include '../includes/header.php';
?>

<div class="admin-page">
    <!-- En-tête de la page administrateur -->
    <div class="admin-header">
        <!-- Titre principal -->
        <h1>📈 Statistiques avancées</h1>

        <!-- Navigation de l'espace administrateur -->
        <div class="admin-nav">
            <a href="/admin/index.php" class="admin-nav-link">Dashboard</a>
            <a href="/admin/utilisateurs.php" class="admin-nav-link">Utilisateurs</a>
            <a href="/admin/questions.php" class="admin-nav-link">Questions</a>
            <a href="/admin/categories.php" class="admin-nav-link">Catégories</a>
            <a href="/admin/stats.php" class="admin-nav-link active">Statistiques</a>
        </div>
    </div>

    <!-- Grille des statistiques -->
    <div class="stats-grid">
        <!-- Section des taux de réussite par catégorie -->
        <div class="admin-section">
            <h2>📊 Taux de réussite par catégorie</h2>

            <!-- Barres de réussite -->
            <div class="stats-bars">
                <!-- Parcourt les statistiques de chaque catégorie -->
                <?php foreach ($stats_cats as $s): ?>
                <div class="stats-bar-item">
                    <!-- Nom de la catégorie et pourcentage -->
                    <div class="sbi-label">
                        <span><?= htmlspecialchars($s['nom']) ?></span>
                        <span><?= $s['taux_reussite'] ?? 0 ?>%</span>
                    </div>

                    <!-- Barre visuelle du taux de réussite -->
                    <div class="sbi-track">
                        <div class="sbi-fill" style="width: <?= $s['taux_reussite'] ?? 0 ?>%"></div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="sbi-meta"><?= $s['nb_parties'] ?> parties · Moy. <?= $s['moyenne'] ?? '--' ?>/20</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section des questions les plus ratées -->
        <div class="admin-section">
            <h2>💀 Questions les plus ratées</h2>

            <!-- Si aucune donnée suffisante n'existe -->
            <?php if (empty($questions_dures)): ?>
                <p class="empty-text">Pas assez de données encore.</p>
            <?php else: ?>
            <div class="hard-questions">
                <!-- Parcourt les questions les plus difficiles -->
                <?php foreach ($questions_dures as $i => $q): ?>
                <div class="hq-item">
                    <!-- Rang de la question -->
                    <div class="hq-rank"><?= $i + 1 ?></div>

                    <div class="hq-body">
                        <!-- Texte raccourci de la question -->
                        <p class="hq-text"><?= htmlspecialchars(substr($q['question'], 0, 80)) ?>...</p>

                        <!-- Détails de la question -->
                        <div class="hq-meta">
                            <span class="cat-badge"><?= htmlspecialchars($q['cat_nom']) ?></span>
                            <span class="hq-fail"><?= $q['taux_echec'] ?>% d'échec</span>
                            <span><?= $q['nb_reponses'] ?> réponses</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Section de l'activité récente -->
        <div class="admin-section full-width">
            <h2>📅 Activité des 14 derniers jours</h2>

            <!-- Graphique simple de l'activité -->
            <div class="activite-chart">
                <?php
                // Récupère la plus grande valeur pour calculer la hauteur des barres
                $max = max(array_column($activite, 'nb') ?: [1]);

                // Parcourt chaque jour d'activité
                foreach ($activite as $a):
                    // Calcule la hauteur de la barre en pourcentage
                    $h = round($a['nb'] / $max * 100);
                ?>
                <div class="ac-col">
                    <!-- Barre verticale -->
                    <div class="ac-bar-wrap">
                        <!-- Nombre de parties du jour -->
                        <span class="ac-nb"><?= $a['nb'] ?></span>

                        <!-- Hauteur de la barre selon l'activité -->
                        <div class="ac-bar" style="height: <?= $h ?>%"></div>
                    </div>

                    <!-- Date du jour -->
                    <div class="ac-day"><?= date('d/m', strtotime($a['jour'])) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php

include '../includes/footer.php';
?>
```