Voici le code commenté simplement :

```php
<?php
// Inclut le fichier de connexion à la base de données
require_once 'config/db.php';

// Inclut le fichier qui gère les sessions
require_once 'config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once 'includes/fonctions.php';

// Oblige l'utilisateur à être connecté
requireConnexion();

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupère toutes les catégories
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

// Prépare une requête pour récupérer les statistiques de l'utilisateur
$stmt = $pdo->prepare('
    SELECT
        COUNT(*) AS nb_parties,
        ROUND(AVG(note_sur_20), 2) AS moyenne,
        MAX(note_sur_20) AS meilleur_score
    FROM tentatives
    WHERE utilisateur_id = ? AND invalide = 0
');

// Exécute la requête avec l'identifiant de l'utilisateur
$stmt->execute([$user_id]);

// Récupère les statistiques
$stats = $stmt->fetch();

// Prépare une requête pour récupérer les 3 dernières parties
$stmt = $pdo->prepare('
    SELECT t.*, c.nom AS cat_nom, c.slug
    FROM tentatives t
    JOIN categories c ON t.categorie_id = c.id
    WHERE t.utilisateur_id = ? AND t.invalide = 0
    ORDER BY t.date_debut DESC
    LIMIT 3
');

// Exécute la requête avec l'identifiant de l'utilisateur
$stmt->execute([$user_id]);

// Récupère les dernières parties
$dernieres = $stmt->fetchAll();

// Prépare une requête pour récupérer les statistiques par catégorie
$stmt = $pdo->prepare('
    SELECT c.id, c.nom, c.slug, cl.meilleure_note, cl.nb_tentatives
    FROM categories c
    LEFT JOIN classement cl ON cl.categorie_id = c.id AND cl.utilisateur_id = ?
    ORDER BY c.id
');

// Exécute la requête avec l'identifiant de l'utilisateur
$stmt->execute([$user_id]);

// Récupère les statistiques des catégories
$stats_cats = $stmt->fetchAll();

// Définit le titre de la page
$page_title = 'Tableau de bord';

// Inclut l'en-tête du site
include 'includes/header.php';
?>

<div class="dashboard">
    <!-- Bloc de bienvenue du tableau de bord -->
    <div class="dash-welcome">
        <div>
            <!-- Affiche le prénom de l'utilisateur connecté -->
            <h1>Bonjour, <?= nettoyerInput($_SESSION['prenom']) ?> 👋</h1>

            <!-- Petit message d'accueil -->
            <p>Prêt à tester tes connaissances ?</p>
        </div>

        <!-- Bouton pour lancer un QCM -->
        <a href="/lancer_qcm.php" class="btn-primary btn-large">⚡ Lancer un QCM</a>
    </div>

    <!-- Bloc des statistiques principales -->
    <div class="dash-stats">
        <!-- Carte du meilleur score -->
        <div class="stat-card">
            <div class="stat-card-icon">🏆</div>
            <div class="stat-card-value"><?= $stats['meilleur_score'] ?? 0 ?>/20</div>
            <div class="stat-card-label">Meilleur score</div>
        </div>

        <!-- Carte de la moyenne générale -->
        <div class="stat-card">
            <div class="stat-card-icon">📊</div>
            <div class="stat-card-value"><?= $stats['moyenne'] ?? 0 ?>/20</div>
            <div class="stat-card-label">Moyenne générale</div>
        </div>

        <!-- Carte du nombre de parties jouées -->
        <div class="stat-card">
            <div class="stat-card-icon">🎮</div>
            <div class="stat-card-value"><?= $stats['nb_parties'] ?? 0 ?></div>
            <div class="stat-card-label">Parties jouées</div>
        </div>
    </div>

    <!-- Grille contenant les catégories et les dernières parties -->
    <div class="dash-grid">
        <!-- Section des catégories -->
        <div class="dash-section">
            <h2 class="section-title">Mes catégories</h2>

            <div class="cats-progress">
                <?php
                // Tableau qui associe chaque catégorie à une icône
                $icons = ['html'=>'🌐','css'=>'🎨','php'=>'🐘','sql'=>'🗄️','reseaux'=>'📡','algo'=>'🧮','sys'=>'💻','culture'=>'💡'];

                // Parcourt les statistiques de chaque catégorie
                foreach ($stats_cats as $cat):
                    // Récupère la meilleure note de la catégorie
                    $note = $cat['meilleure_note'] ?? null;

                    // Calcule le pourcentage de progression
                    $pct = $note ? round(($note / 20) * 100) : 0;

                    // Récupère l'icône de la catégorie
                    $icon = $icons[$cat['slug']] ?? '📚';
                ?>
                <!-- Élément de progression d'une catégorie -->
                <div class="cat-progress-item">
                    <div class="cpi-left">
                        <!-- Icône de la catégorie -->
                        <span class="cpi-icon"><?= $icon ?></span>

                        <div>
                            <!-- Nom de la catégorie -->
                            <div class="cpi-name"><?= htmlspecialchars($cat['nom']) ?></div>

                            <!-- Nombre de tentatives dans cette catégorie -->
                            <div class="cpi-sub"><?= $cat['nb_tentatives'] ?? 0 ?> tentative(s)</div>
                        </div>
                    </div>

                    <div class="cpi-right">
                        <!-- Barre de progression -->
                        <div class="cpi-bar-wrap">
                            <div class="cpi-bar" style="width: <?= $pct ?>%"></div>
                        </div>

                        <!-- Affiche la note, ou deux tirets si aucune note -->
                        <span class="cpi-note"><?= $note !== null ? $note . '/20' : '--' ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section des dernières parties -->
        <div class="dash-section">
            <h2 class="section-title">Dernières parties</h2>

            <!-- Si aucune partie n'a encore été jouée -->
            <?php if (empty($dernieres)): ?>
                <div class="empty-state">
                    <p>🎯 Aucune partie jouée encore.</p>

                    <!-- Bouton pour commencer un QCM -->
                    <a href="/lancer_qcm.php" class="btn-primary">Commencer maintenant</a>
                </div>
            <?php else: ?>
                <!-- Liste des dernières parties -->
                <div class="recent-list">
                    <?php foreach ($dernieres as $t):
                        // Récupère la mention selon la note
                        $mention = getMention($t['note_sur_20']);
                    ?>
                    <!-- Élément d'une partie récente -->
                    <div class="recent-item">
                        <div class="ri-left">
                            <!-- Nom de la catégorie -->
                            <span class="ri-cat"><?= htmlspecialchars($t['cat_nom']) ?></span>

                            <!-- Date de la partie -->
                            <span class="ri-date"><?= formaterDate($t['date_debut']) ?></span>
                        </div>

                        <div class="ri-right">
                            <!-- Note obtenue -->
                            <span class="ri-note"><?= $t['note_sur_20'] ?>/20</span>

                            <!-- Mention obtenue -->
                            <span class="badge <?= $mention['class'] ?>"><?= $mention['label'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Lien vers tout l'historique -->
                <a href="/historique.php" class="link-all">Voir tout l'historique →</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Inclut le pied de page du site
include 'includes/footer.php';
?>
```