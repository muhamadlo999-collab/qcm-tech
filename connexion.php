<?php
// Inclut le fichier de connexion à la base de données
require_once 'config/db.php';

// Inclut le fichier qui gère les sessions
require_once 'config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once 'includes/fonction.php';

// Si l'utilisateur est déjà connecté, il est redirigé vers le tableau de bord
if (estConnecte()) redirect('tableau_de_bord.php');

$erreurs = [];
$email = '';

// Vérifie si le formulaire a été envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = nettoyerInput($_POST['email'] ?? '');
    $mdp = $_POST['mot_de_passe'] ?? '';

    if (empty($email)) $erreurs['email'] = "L'email est requis.";
    if (empty($mdp)) $erreurs['mdp'] = 'Le mot de passe est requis.';

    if (empty($erreurs)) {
        // Cherche l'utilisateur avec son email
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Si l'utilisateur existe et que le mot de passe correspond
        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            
            // On enregistre les infos de l'utilisateur dans la Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['role'] = $user['role']; // 'user' ou 'admin'
            $_SESSION['bloque'] = $user['bloque'] ?? 0;

            // Redirection vers son espace
            redirect('tableau_de_bord.php');
        } else {
            // Message générique par sécurité (on ne dit pas si c'est l'email ou le mdp qui est faux)
            $erreurs['global'] = 'Email ou mot de passe incorrect.';
        }
    }
}

$page_title = 'Connexion';
include 'includes/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">🔑</div>
            <h1>Connexion</h1>
            <p>Ravi de vous revoir ! Connectez-vous pour jouer.</p>
        </div>

        <?php if (isset($_GET['succes']) && $_GET['succes'] === 'inscription'): ?>
            <div style="background-color: rgba(34, 197, 94, 0.2); color: #22c55e; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; text-align: center;">
                Inscription réussie ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>

        <?php if (isset($erreurs['global'])): ?>
            <div style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; text-align: center;">
                <?= $erreurs['global'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form" novalidate>
            <div class="form-group <?= isset($erreurs['email']) ? 'error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="ton@email.com" autocomplete="email">
                <?php if (isset($erreurs['email'])): ?><span class="form-error"><?= $erreurs['email'] ?></span><?php endif; ?>
            </div>

            <div class="form-group <?= isset($erreurs['mdp']) ? 'error' : '' ?>">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Ton mot de passe" autocomplete="current-password">
                <?php if (isset($erreurs['mdp'])): ?><span class="form-error"><?= $erreurs['mdp'] ?></span><?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>

        <div class="auth-footer">
            Pas encore de compte ? <a href="inscription.php">S'inscrire</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>