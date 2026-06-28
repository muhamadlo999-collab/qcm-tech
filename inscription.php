<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/fonction.php';

if (estConnecte()) redirect('tableau_de_bord.php');

$erreurs = [];
$valeurs = ['nom' => '', 'prenom' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = nettoyerInput($_POST['nom'] ?? '');
    $prenom   = nettoyerInput($_POST['prenom'] ?? '');
    $email    = nettoyerInput($_POST['email'] ?? '');
    $mdp      = $_POST['mot_de_passe'] ?? '';
    $mdp_conf = $_POST['mdp_confirmation'] ?? '';

    $valeurs = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email];

    if (empty($nom))    $erreurs['nom']    = 'Le nom est requis.';
    if (empty($prenom)) $erreurs['prenom'] = 'Le prénom est requis.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs['email'] = 'Email invalide.';
    if (strlen($mdp) < 6) $erreurs['mdp'] = 'Le mot de passe doit faire au moins 6 caractères.';
    if ($mdp !== $mdp_conf) $erreurs['mdp_conf'] = 'Les mots de passe ne correspondent pas.';

    if (empty($erreurs)) {
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erreurs['email'] = 'Cet email est déjà utilisé.';
        } else {
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nom, $prenom, $email, $hash]);
            redirect('connexion.php?succes=inscription');
        }
    }
}

$page_title = 'Inscription';
include __DIR__ . '/includes/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">📝</div>
            <h1>Créer un compte</h1>
            <p>Rejoins le quiz et teste tes connaissances tech</p>
        </div>

        <form method="POST" class="auth-form" novalidate>
            <div class="form-row">
                <div class="form-group <?= isset($erreurs['prenom']) ? 'error' : '' ?>">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= $valeurs['prenom'] ?>" placeholder="Ton prénom" autocomplete="given-name">
                    <?php if (isset($erreurs['prenom'])): ?><span class="form-error"><?= $erreurs['prenom'] ?></span><?php endif; ?>
                </div>

                <div class="form-group <?= isset($erreurs['nom']) ? 'error' : '' ?>">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= $valeurs['nom'] ?>" placeholder="Ton nom" autocomplete="family-name">
                    <?php if (isset($erreurs['nom'])): ?><span class="form-error"><?= $erreurs['nom'] ?></span><?php endif; ?>
                </div>
            </div>

            <div class="form-group <?= isset($erreurs['email']) ? 'error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $valeurs['email'] ?>" placeholder="ton@email.com" autocomplete="email">
                <?php if (isset($erreurs['email'])): ?><span class="form-error"><?= $erreurs['email'] ?></span><?php endif; ?>
            </div>

            <div class="form-group <?= isset($erreurs['mdp']) ? 'error' : '' ?>">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="6 caractères minimum" autocomplete="new-password">
                <?php if (isset($erreurs['mdp'])): ?><span class="form-error"><?= $erreurs['mdp'] ?></span><?php endif; ?>
            </div>

            <div class="form-group <?= isset($erreurs['mdp_conf']) ? 'error' : '' ?>">
                <label for="mdp_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="mdp_confirmation" name="mdp_confirmation" placeholder="Répète le mot de passe" autocomplete="new-password">
                <?php if (isset($erreurs['mdp_conf'])): ?><span class="form-error"><?= $erreurs['mdp_conf'] ?></span><?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>

        <div class="auth-footer">
            Déjà un compte ? <a href="connexion.php">Se connecter</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>