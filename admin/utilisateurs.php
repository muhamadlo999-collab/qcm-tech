
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

// Vérifie si une action et un utilisateur ont été envoyés
if (isset($_POST['action'], $_POST['user_id'])) {
    // Récupère l'identifiant de l'utilisateur
    $uid = (int)$_POST['user_id'];

    // Si l'action est de bloquer l'utilisateur
    if ($_POST['action'] === 'bloquer') {
        // Bloque l'utilisateur, sauf s'il est administrateur
        $pdo->prepare('UPDATE utilisateurs SET bloque = 1 WHERE id = ? AND role != "admin"')->execute([$uid]);

        // Message de confirmation
        $msg = 'Utilisateur bloqué.';
    } elseif ($_POST['action'] === 'debloquer') {
        // Débloque l'utilisateur
        $pdo->prepare('UPDATE utilisateurs SET bloque = 0 WHERE id = ?')->execute([$uid]);

        // Message de confirmation
        $msg = 'Utilisateur débloqué.';
    } elseif ($_POST['action'] === 'supprimer') {
        // Supprime l'utilisateur, sauf s'il est administrateur
        $pdo->prepare('DELETE FROM utilisateurs WHERE id = ? AND role != "admin"')->execute([$uid]);

        // Message de confirmation
        $msg = 'Utilisateur supprimé.';
    }
}

// Nettoie le texte de recherche reçu depuis l'URL
$search = nettoyerInput($_GET['q'] ?? '');

// Prépare la requête pour récupérer les utilisateurs
$sql = 'SELECT u.*, (SELECT COUNT(*) FROM tentatives WHERE utilisateur_id = u.id AND invalide = 0) AS nb_parties,
           (SELECT ROUND(AVG(note_sur_20),1) FROM tentatives WHERE utilisateur_id = u.id AND invalide = 0) AS moyenne
           FROM utilisateurs u WHERE role = "user"';

// Paramètres de la requête
$params = [];

// Si une recherche est faite
if ($search) {
    // Ajoute une condition de recherche sur le nom, prénom ou email
    $sql .= ' AND (u.nom LIKE ? OR u.prenom LIKE ? OR u.email LIKE ?)';

    // Ajoute les valeurs recherchées aux paramètres
    $params = ["%$search%", "%$search%", "%$search%"];
}

// Trie les utilisateurs du plus récent au plus ancien
$sql .= ' ORDER BY u.created_at DESC';

// Prépare la requête finale
$stmt = $pdo->prepare($sql);

// Exécute la requête avec les paramètres
$stmt->execute($params);

// Récupère les utilisateurs
$users = $stmt->fetchAll();

// Définit le titre de la page
$page_title = 'Gestion utilisateurs';

// Inclut l'en-tête du site
include '../includes/header.php';
?>

<div class="admin-page">
    <!-- En-tête de la page administrateur -->
    <div class="admin-header">
        <!-- Titre principal -->
        <h1>👥 Gestion des utilisateurs</h1>

        <!-- Navigation de l'espace administrateur -->
        <div class="admin-nav">
            <a href="/admin/index.php" class="admin-nav-link">Dashboard</a>
            <a href="/admin/utilisateurs.php" class="admin-nav-link active">Utilisateurs</a>
            <a href="/admin/questions.php" class="admin-nav-link">Questions</a>
            <a href="/admin/categories.php" class="admin-nav-link">Catégories</a>
            <a href="/admin/stats.php" class="admin-nav-link">Statistiques</a>
        </div>
    </div>

    <!-- Affiche le message de confirmation s'il existe -->
    <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>

    <!-- Barre de recherche et compteur -->
    <div class="admin-toolbar">
        <!-- Formulaire de recherche -->
        <form method="GET" class="search-form">
            <!-- Champ de recherche -->
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par nom ou email...">

            <!-- Bouton pour lancer la recherche -->
            <button type="submit" class="btn-primary">Rechercher</button>

            <!-- Si une recherche est active, affiche un bouton pour réinitialiser -->
            <?php if ($search): ?><a href="/admin/utilisateurs.php" class="btn-secondary">Réinitialiser</a><?php endif; ?>
        </form>

        <!-- Nombre d'utilisateurs affichés -->
        <span class="count-badge"><?= count($users) ?> utilisateur(s)</span>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom Nom</th>
                    <th>Email</th>
                    <th>Parties</th>
                    <th>Moyenne</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <!-- Parcourt les utilisateurs -->
                <?php foreach ($users as $u): ?>
                <tr class="<?= $u['bloque'] ? 'row-invalid' : '' ?>">
                    <!-- Identifiant de l'utilisateur -->
                    <td>#<?= $u['id'] ?></td>

                    <!-- Nom complet de l'utilisateur -->
                    <td><strong><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></strong></td>

                    <!-- Email de l'utilisateur -->
                    <td><?= htmlspecialchars($u['email']) ?></td>

                    <!-- Nombre de parties jouées -->
                    <td><?= $u['nb_parties'] ?></td>

                    <!-- Moyenne de l'utilisateur -->
                    <td><?= $u['moyenne'] ?? '--' ?>/20</td>

                    <!-- Statut de l'utilisateur -->
                    <td><?= $u['bloque'] ? '<span class="badge mention-insuffisant">Bloqué</span>' : '<span class="badge mention-excellent">Actif</span>' ?></td>

                    <!-- Date d'inscription -->
                    <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>

                    <!-- Actions possibles -->
                    <td>
                        <form method="POST" style="display:inline">
                            <!-- Identifiant caché de l'utilisateur -->
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">

                            <!-- Affiche le bouton adapté selon le statut -->
                            <?php if ($u['bloque']): ?>
                                <button name="action" value="debloquer" class="btn-sm btn-success">Débloquer</button>
                            <?php else: ?>
                                <button name="action" value="bloquer" class="btn-sm btn-warning">Bloquer</button>
                            <?php endif; ?>

                            <!-- Bouton pour supprimer avec confirmation -->
                            <button name="action" value="supprimer" class="btn-sm btn-danger"
                                onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Inclut le pied de page du site
include '../includes/footer.php';
?>
```