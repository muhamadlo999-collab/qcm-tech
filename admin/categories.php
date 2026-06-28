
<?php
// Inclut le fichier de connexion à la base de données
require_once '../config/db.php';

// Inclut le fichier qui gère les sessions
require_once '../config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once '../includes/fonctions.php';

// Oblige l'utilisateur à être administrateur
requireAdmin();

// Message affiché après une modification
$msg = '';

// Vérifie si le formulaire a été envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère l'identifiant de la catégorie
    $id = (int)$_POST['cat_id'];

    // Récupère la durée en minutes
    $duree = (int)$_POST['duree_minutes'];

    // Récupère le nombre de questions par QCM
    $nb = (int)$_POST['nb_questions_qcm'];

    // Vérifie que les valeurs sont correctes
    if ($duree >= 1 && $nb >= 1) {
        // Met à jour la catégorie dans la base de données
        $pdo->prepare('UPDATE categories SET duree_minutes = ?, nb_questions_qcm = ? WHERE id = ?')
            ->execute([$duree, $nb, $id]);

        // Prépare le message de confirmation
        $msg = '✅ Catégorie mise à jour.';
    }
}

// Prépare la requête pour récupérer les catégories et le nombre de questions saisies
$stmt = $pdo->query('
    SELECT c.*, (SELECT COUNT(*) FROM questions WHERE categorie_id = c.id) AS nb_saisies
    FROM categories c ORDER BY c.id
');

// Récupère toutes les catégories
$categories = $stmt->fetchAll();

// Définit le titre de la page
$page_title = 'Gestion catégories';

// Inclut l'en-tête du site
include '../includes/header.php';
?>

<div class="admin-page">
    <!-- En-tête de la page administrateur -->
    <div class="admin-header">
        <!-- Titre principal -->
        <h1>📂 Gestion des catégories</h1>

        <!-- Navigation de l'espace administrateur -->
        <div class="admin-nav">
            <a href="/admin/index.php" class="admin-nav-link">Dashboard</a>
            <a href="/admin/utilisateurs.php" class="admin-nav-link">Utilisateurs</a>
            <a href="/admin/questions.php" class="admin-nav-link">Questions</a>
            <a href="/admin/categories.php" class="admin-nav-link active">Catégories</a>
            <a href="/admin/stats.php" class="admin-nav-link">Statistiques</a>
        </div>
    </div>

    <!-- Affiche le message de confirmation s'il existe -->
    <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>

    <!-- Grille des catégories -->
    <div class="cats-admin-grid">
        <!-- Parcourt toutes les catégories -->
        <?php foreach ($categories as $cat):
            // Calcule le pourcentage de questions déjà saisies
            $pct = $cat['nb_questions_total'] > 0
                ? round($cat['nb_saisies'] / $cat['nb_questions_total'] * 100)
                : 0;
        ?>
        <!-- Carte d'une catégorie -->
        <div class="cat-admin-card">
            <!-- En-tête de la carte -->
            <div class="cac-header">
                <!-- Nom de la catégorie -->
                <h3><?= htmlspecialchars($cat['nom']) ?></h3>

                <!-- Slug de la catégorie -->
                <span class="cac-slug"><?= $cat['slug'] ?></span>
            </div>

            <!-- Progression des questions saisies -->
            <div class="cac-progress">
                <div class="progress-label">
                    <span>Questions saisies</span>
                    <span><?= $cat['nb_saisies'] ?> / <?= $cat['nb_questions_total'] ?></span>
                </div>

                <!-- Barre de progression -->
                <div class="progress-track">
                    <div class="progress-fill <?= $pct >= 100 ? 'complete' : '' ?>" style="width: <?= $pct ?>%"></div>
                </div>
            </div>

            <!-- Formulaire de modification de la catégorie -->
            <form method="POST" class="cac-form">
                <!-- Identifiant caché de la catégorie -->
                <input type="hidden" name="cat_id" value="<?= $cat['id'] ?>">

                <!-- Champs modifiables -->
                <div class="cac-fields">
                    <div class="form-group">
                        <label>Questions par QCM</label>

                        <!-- Champ pour modifier le nombre de questions par QCM -->
                        <input type="number" name="nb_questions_qcm" value="<?= $cat['nb_questions_qcm'] ?>" min="1" max="<?= $cat['nb_questions_total'] ?>">
                    </div>

                    <div class="form-group">
                        <label>Durée (minutes)</label>

                        <!-- Champ pour modifier la durée du QCM -->
                        <input type="number" name="duree_minutes" value="<?= $cat['duree_minutes'] ?>" min="1" max="60">
                    </div>
                </div>

                <!-- Bouton pour enregistrer les modifications -->
                <button type="submit" class="btn-primary btn-sm">Mettre à jour</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php

include '../includes/footer.php';
?>
```