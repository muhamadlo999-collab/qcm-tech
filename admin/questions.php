
<?php
// Inclut le fichier de connexion à la base de données
require_once '../config/db.php';

// Inclut le fichier qui gère les sessions
require_once '../config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once '../includes/fonctions.php';

// Oblige l'utilisateur à être administrateur
requireAdmin();

// Message affiché après une action
$msg = '';

// Variable qui contiendra la question à modifier
$edit = null;

// Récupère le filtre de catégorie dans l'URL
$cat_id = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Vérifie si le formulaire a été envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère l'action demandée
    $action = $_POST['action'] ?? '';

    // Si l'action est ajouter ou modifier
    if ($action === 'ajouter' || $action === 'modifier') {
        // Récupère l'identifiant de la catégorie
        $cid = (int)$_POST['categorie_id'];

        // Nettoie le texte de la question
        $q = nettoyerInput($_POST['question']);

        // Nettoie les réponses
        $r1 = nettoyerInput($_POST['reponse1']);
        $r2 = nettoyerInput($_POST['reponse2']);
        $r3 = nettoyerInput($_POST['reponse3']);
        $r4 = nettoyerInput($_POST['reponse4']);

        // Récupère le numéro de la bonne réponse
        $bon = (int)$_POST['bonne_reponse'];

        // Si on ajoute une nouvelle question
        if ($action === 'ajouter') {
            // Ajoute la question dans la base de données
            $pdo->prepare('INSERT INTO questions (categorie_id,question,reponse1,reponse2,reponse3,reponse4,bonne_reponse) VALUES (?,?,?,?,?,?,?)')
                ->execute([$cid, $q, $r1, $r2, $r3, $r4, $bon]);

            // Message de confirmation
            $msg = '✅ Question ajoutée.';
        } else {
            // Modifie la question dans la base de données
            $pdo->prepare('UPDATE questions SET categorie_id=?,question=?,reponse1=?,reponse2=?,reponse3=?,reponse4=?,bonne_reponse=? WHERE id=?')
                ->execute([$cid, $q, $r1, $r2, $r3, $r4, $bon, (int)$_POST['question_id']]);

            // Message de confirmation
            $msg = '✅ Question modifiée.';
        }
    } elseif ($action === 'supprimer') {
        // Supprime la question dans la base de données
        $pdo->prepare('DELETE FROM questions WHERE id = ?')->execute([(int)$_POST['question_id']]);

        // Message de confirmation
        $msg = '🗑️ Question supprimée.';
    }
}

// Vérifie si une question doit être modifiée
if (isset($_GET['edit'])) {
    // Prépare la requête pour récupérer la question
    $stmt = $pdo->prepare('SELECT * FROM questions WHERE id = ?');

    // Exécute la requête avec l'identifiant de la question
    $stmt->execute([(int)$_GET['edit']]);

    // Stocke la question à modifier
    $edit = $stmt->fetch();
}

// Récupère toutes les catégories
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

// Prépare la requête pour récupérer les questions avec leur catégorie
$sql = 'SELECT q.*, c.nom AS cat_nom FROM questions q JOIN categories c ON q.categorie_id = c.id';

// Paramètres de la requête
$params = [];

// Si une catégorie est sélectionnée, ajoute un filtre
if ($cat_id > 0) {
    // Ajoute la condition sur la catégorie
    $sql .= ' WHERE q.categorie_id = ?';

    // Ajoute l'identifiant de la catégorie aux paramètres
    $params[] = $cat_id;
}

// Trie les questions par catégorie puis par identifiant
$sql .= ' ORDER BY q.categorie_id, q.id';

// Prépare la requête finale
$stmt = $pdo->prepare($sql);

// Exécute la requête
$stmt->execute($params);

// Récupère les questions
$questions = $stmt->fetchAll();

// Définit le titre de la page
$page_title = 'Gestion questions';

// Inclut l'en-tête du site
include '../includes/header.php';
?>

<div class="admin-page">
    <!-- En-tête de la page d'administration -->
    <div class="admin-header">
        <!-- Titre principal -->
        <h1>❓ Gestion des questions</h1>

        <!-- Navigation de l'espace administrateur -->
        <div class="admin-nav">
            <a href="/admin/index.php" class="admin-nav-link">Dashboard</a>
            <a href="/admin/utilisateurs.php" class="admin-nav-link">Utilisateurs</a>
            <a href="/admin/questions.php" class="admin-nav-link active">Questions</a>
            <a href="/admin/categories.php" class="admin-nav-link">Catégories</a>
            <a href="/admin/stats.php" class="admin-nav-link">Statistiques</a>
        </div>
    </div>

    <!-- Affiche un message si une action a été faite -->
    <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>

    <!-- Grille principale -->
    <div class="admin-grid">
        <!-- Formulaire d'ajout ou de modification -->
        <div class="admin-section">
            <h2><?= $edit ? '✏️ Modifier la question' : '➕ Ajouter une question' ?></h2>

            <form method="POST" class="admin-form">
                <!-- Indique si le formulaire sert à ajouter ou modifier -->
                <input type="hidden" name="action" value="<?= $edit ? 'modifier' : 'ajouter' ?>">

                <!-- Si on modifie, stocke l'identifiant de la question -->
                <?php if ($edit): ?><input type="hidden" name="question_id" value="<?= $edit['id'] ?>"><?php endif; ?>

                <!-- Choix de la catégorie -->
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="categorie_id" required>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($edit && $edit['categorie_id'] == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Champ de la question -->
                <div class="form-group">
                    <label>Question</label>
                    <textarea name="question" rows="3" required><?= $edit ? htmlspecialchars($edit['question']) : '' ?></textarea>
                </div>

                <!-- Champs des 4 réponses possibles -->
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="form-group form-inline">
                    <label>
                        <!-- Bouton radio pour choisir la bonne réponse -->
                        <input type="radio" name="bonne_reponse" value="<?= $i ?>" <?= ($edit && $edit['bonne_reponse'] == $i) ? 'checked' : '' ?> required>
                        Réponse <?= $i ?>
                    </label>

                    <!-- Texte de la réponse -->
                    <input type="text" name="reponse<?= $i ?>" value="<?= $edit ? htmlspecialchars($edit['reponse'.$i]) : '' ?>" required placeholder="Réponse <?= $i ?>">
                </div>
                <?php endfor; ?>

                <!-- Aide pour choisir la bonne réponse -->
                <div class="form-hint">Cocher le bouton radio à côté de la bonne réponse.</div>

                <!-- Boutons du formulaire -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary"><?= $edit ? 'Modifier' : 'Ajouter' ?></button>

                    <!-- Bouton pour annuler la modification -->
                    <?php if ($edit): ?><a href="/admin/questions.php" class="btn-secondary">Annuler</a><?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Liste des questions -->
        <div class="admin-section">
            <div class="admin-toolbar">
                <!-- Boutons de filtre par catégorie -->
                <div class="filter-row">
                    <?php foreach ($categories as $c): ?>
                    <a href="/admin/questions.php?cat=<?= $c['id'] ?>" class="filter-btn <?= $cat_id == $c['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($c['nom']) ?>
                    </a>
                    <?php endforeach; ?>

                    <!-- Bouton pour afficher toutes les questions -->
                    <a href="/admin/questions.php" class="filter-btn <?= $cat_id === 0 ? 'active' : '' ?>">Tout</a>
                </div>

                <!-- Nombre de questions affichées -->
                <span class="count-badge"><?= count($questions) ?> question(s)</span>
            </div>

            <!-- Liste des questions existantes -->
            <div class="questions-list">
                <?php foreach ($questions as $q): ?>
                <div class="q-admin-item">
                    <div class="qai-body">
                        <!-- Nom de la catégorie -->
                        <span class="cat-badge"><?= htmlspecialchars($q['cat_nom']) ?></span>

                        <!-- Texte raccourci de la question -->
                        <p class="qai-text"><?= htmlspecialchars(substr($q['question'], 0, 100)) ?>...</p>

                        <!-- Numéro de la bonne réponse -->
                        <span class="qai-bon">✓ Réponse <?= $q['bonne_reponse'] ?></span>
                    </div>

                    <!-- Actions modifier / supprimer -->
                    <div class="qai-actions">
                        <a href="/admin/questions.php?edit=<?= $q['id'] ?>" class="btn-sm btn-warning">Modifier</a>

                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="question_id" value="<?= $q['id'] ?>">

                            <!-- Bouton avec confirmation avant suppression -->
                            <button class="btn-sm btn-danger" onclick="return confirm('Supprimer cette question ?')">Supprimer</button>
                        </form>
                    </div>
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