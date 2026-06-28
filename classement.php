
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

// Récupère la catégorie choisie dans l'URL, sinon prend la première catégorie
$cat_id = isset($_GET['cat']) ? (int)$_GET['cat'] : $categories[0]['id'];

// Variable qui contiendra la catégorie active
$cat_active = null;

// Cherche la catégorie active dans la liste des catégories
foreach ($categories as $c) {
    // Si l'identifiant correspond à la catégorie choisie
    if ($c['id'] === $cat_id) {
        // Stocke cette catégorie comme catégorie active
        $cat_active = $c;

        // Arrête la boucle
        break;
    }
}

// Si aucune catégorie valide n'a été trouvée
if (!$cat_active) {
    // Reprend la première catégorie
    $cat_id = $categories[0]['id'];

    // Définit la première catégorie comme catégorie active
    $cat_active = $categories[0];
}

// Prépare la requête pour récupérer le classement de la catégorie
$stmt = $pdo->prepare('
    SELECT cl.*, u.prenom, u.nom,
           ROW_NUMBER() OVER (ORDER BY cl.meilleure_note DESC, cl.moyenne DESC) AS rang
    FROM classement cl
    JOIN utilisateurs u ON cl.utilisateur_id = u.id
    WHERE cl.categorie_id = ?
    ORDER BY cl.meilleure_note DESC, cl.moyenne DESC
    LIMIT 50
');

// Exécute la requête avec la catégorie choisie
$stmt->execute([$cat_id]);

// Récupère les lignes du classement
$classement = $stmt->fetchAll();

// Variable qui contiendra le rang de l'utilisateur connecté
$mon_rang = null;

// Parcourt le classement pour trouver le rang de l'utilisateur connecté
foreach ($classement as $row) {
    // Si la ligne correspond à l'utilisateur connecté
    if ($row['utilisateur_id'] == $user_id) {
        // Stocke son rang
        $mon_rang = $row['rang'];

        // Arrête la boucle
        break;
    }
}

// Associe chaque catégorie à une icône
$icons = ['html'=>'🌐','css'=>'🎨','php'=>'🐘','sql'=>'🗄️','reseaux'=>'📡','algo'=>'🧮','sys'=>'💻','culture'=>'💡'];

// Définit le titre de la page
$page_title = 'Classement';

// Inclut l'en-tête du site
include 'includes/header.php';
?>

<div class="classement-page">
    <!-- En-tête de la page classement -->
    <div class="page-header">
        <!-- Titre principal -->
        <h1>🏆 Classement</h1>

        <!-- Petit texte de présentation -->
        <p>Les meilleurs joueurs par catégorie</p>
    </div>

    <!-- Onglets des catégories -->
    <div class="class-tabs">
        <!-- Parcourt toutes les catégories -->
        <?php foreach ($categories as $cat): ?>
        <a href="/classement.php?cat=<?= $cat['id'] ?>"
           class="class-tab <?= $cat['id'] == $cat_id ? 'active' : '' ?>">
            <!-- Affiche l'icône et le nom de la catégorie -->
            <?= ($icons[$cat['slug']] ?? '📚') . ' ' . htmlspecialchars($cat['nom']) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Si le classement est vide -->
    <?php if (empty($classement)): ?>
        <div class="empty-state">
            <!-- Message affiché s'il n'y a aucun score -->
            <p>🎯 Aucun score enregistré pour cette catégorie.</p>

            <!-- Lien pour commencer un QCM -->
            <a href="/lancer_qcm.php" class="btn-primary">Être le premier !</a>
        </div>
    <?php else: ?>

        <!-- Si le classement contient au moins 3 joueurs -->
        <?php if (count($classement) >= 3): ?>
        <div class="podium">
            <!-- Deuxième place du podium -->
            <div class="podium-place second">
                <div class="podium-avatar">🥈</div>
                <div class="podium-name"><?= htmlspecialchars($classement[1]['prenom']) ?></div>
                <div class="podium-score"><?= $classement[1]['meilleure_note'] ?>/20</div>
                <div class="podium-block p2">2</div>
            </div>

            <!-- Première place du podium -->
            <div class="podium-place first">
                <div class="podium-crown">👑</div>
                <div class="podium-avatar">🥇</div>
                <div class="podium-name"><?= htmlspecialchars($classement[0]['prenom']) ?></div>
                <div class="podium-score"><?= $classement[0]['meilleure_note'] ?>/20</div>
                <div class="podium-block p1">1</div>
            </div>

            <!-- Troisième place du podium -->
            <div class="podium-place third">
                <div class="podium-avatar">🥉</div>
                <div class="podium-name"><?= htmlspecialchars($classement[2]['prenom']) ?></div>
                <div class="podium-score"><?= $classement[2]['meilleure_note'] ?>/20</div>
                <div class="podium-block p3">3</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Si l'utilisateur connecté est dans le classement affiché -->
        <?php if ($mon_rang): ?>
        <div class="my-rank-banner">
            🎯 Ta position dans cette catégorie : <strong>#<?= $mon_rang ?></strong>
        </div>
        <?php endif; ?>

        <!-- Tableau du classement -->
        <div class="class-table-wrap">
            <table class="class-table">
                <thead>
                    <tr>
                        <!-- Colonne du rang -->
                        <th>#</th>

                        <!-- Colonne du joueur -->
                        <th>Joueur</th>

                        <!-- Colonne de la meilleure note -->
                        <th>Meilleure note</th>

                        <!-- Colonne de la moyenne -->
                        <th>Moyenne</th>

                        <!-- Colonne du nombre de parties -->
                        <th>Parties</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Parcourt chaque ligne du classement -->
                    <?php foreach ($classement as $row): ?>
                    <tr class="<?= $row['utilisateur_id'] == $user_id ? 'my-row' : '' ?>">
                        <td>
                            <?php
                            // Affiche une médaille pour les 3 premiers
                            if ($row['rang'] == 1) echo '🥇';
                            elseif ($row['rang'] == 2) echo '🥈';
                            elseif ($row['rang'] == 3) echo '🥉';
                            else echo '#' . $row['rang'];
                            ?>
                        </td>
                        <td>
                            <!-- Affiche le prénom du joueur -->
                            <strong><?= htmlspecialchars($row['prenom']) ?></strong>

                            <!-- Si cette ligne correspond à l'utilisateur connecté -->
                            <?php if ($row['utilisateur_id'] == $user_id): ?>
                                <!-- Affiche un badge Moi -->
                                <span class="you-badge">Moi</span>
                            <?php endif; ?>
                        </td>

                        <!-- Affiche la meilleure note -->
                        <td><strong><?= $row['meilleure_note'] ?>/20</strong></td>

                        <!-- Affiche la moyenne -->
                        <td><?= round($row['moyenne'], 1) ?>/20</td>

                        <!-- Affiche le nombre de tentatives -->
                        <td><?= $row['nb_tentatives'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php

include 'includes/footer.php';
?>
```