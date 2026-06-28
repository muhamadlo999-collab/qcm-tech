<?php
// Inclut le fichier de connexion à la base de données
require_once 'config/db.php';

// Inclut le fichier qui gère les sessions
require_once 'config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once 'includes/fonctions.php';

// Si l'utilisateur est déjà connecté, il est redirigé vers le tableau de bord
if (estConnecte()) redirect('/tableau_de_bord.php');

// Tableau qui contiendra les erreurs du formulaire
$erreurs = [];

// Valeurs par défaut des champs du formulaire
$valeurs = ['nom' => '', 'prenom' => '', 'email' => ''];

// Vérifie si le formulaire a été envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoie le nom envoyé par l'utilisateur
    $nom = nettoyerInput($_POST['nom'] ?? '');

    // Nettoie le prénom envoyé par l'utilisateur
    $prenom = nettoyerInput($_POST['prenom'] ?? '');

    // Nettoie l'email envoyé par l'utilisateur
    $email = nettoyerInput($_POST['email'] ?? '');

    // Récupère le mot de passe envoyé
    $mdp = $_POST['mot_de_passe'] ?? '';

    // Récupère la confirmation du mot de passe
    $mdp_conf = $_POST['mdp_confirmation'] ?? '';

    // Garde les valeurs saisies pour les réafficher dans le formulaire
    $valeurs = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email];

    // Vérifie que le nom n'est pas vide
    if (empty($nom)) $erreurs['nom'] = 'Le nom est requis.';

    // Vérifie que le prénom n'est pas vide
    if (empty($prenom)) $erreurs['prenom'] = 'Le prénom est requis.';

    // Vérifie que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs['email'] = 'Email invalide.';

    // Vérifie que le mot de passe contient au moins 6 caractères
    if (strlen($mdp) < 6) $erreurs['mdp'] = 'Le mot de passe doit faire au moins 6 caractères.';

    // Vérifie que les deux mots de passe sont identiques
    if ($mdp !== $mdp_conf) $erreurs['mdp_conf'] = 'Les mots de passe ne correspondent pas.';

    // Si aucune erreur n'a été trouvée
    if (empty($erreurs)) {
        // Prépare une requête pour vérifier si l'email existe déjà
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = ?');

        // Exécute la requête avec l'email saisi
        $stmt->execute([$email]);

        // Si un utilisateur existe déjà avec cet email
        if ($stmt->fetch()) {
            // Ajoute une erreur pour l'email
            $erreurs['email'] = 'Cet email est déjà utilisé.';
        } else {
            // Crypte le mot de passe avant de l'enregistrer
            $hash = password_hash($mdp, PASSWORD_DEFAULT);

            // Prépare la requête pour ajouter un nouvel utilisateur
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)');

            // Ajoute l'utilisateur dans la base de données
            $stmt->execute([$nom, $prenom, $email, $hash]);

            // Redirige vers la page de connexion avec un message de succès
            redirect('/connexion.php?succes=inscription');
        }
    }
}

// Définit le titre de la page
$page_title = 'Inscription';

// Inclut l'en-tête du site
include 'includes/header.php';
?>

<div class="auth-page">
    <!-- Bloc principal de la page d'inscription -->
    <div class="auth-card">
        <!-- En-tête du formulaire -->
        <div class="auth-header">
            <!-- Icône affichée en haut -->
            <div class="auth-icon">📝</div>

            <!-- Titre principal -->
            <h1>Créer un compte</h1>

            <!-- Petit texte de présentation -->
            <p>Rejoins le quiz et teste tes connaissances tech</p>
        </div>

        <!-- Formulaire d'inscription -->
        <form method="POST" class="auth-form" novalidate>

            <!-- Ligne contenant le prénom et le nom -->
            <div class="form-row">
                <!-- Groupe du champ prénom, avec classe erreur si besoin -->
                <div class="form-group <?= isset($erreurs['prenom']) ? 'error' : '' ?>">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= $valeurs['prenom'] ?>" placeholder="Ton prénom" autocomplete="given-name">

                    <!-- Affiche l'erreur du prénom si elle existe -->
                    <?php if (isset($erreurs['prenom'])): ?><span class="form-error"><?= $erreurs['prenom'] ?></span><?php endif; ?>
                </div>

                <!-- Groupe du champ nom, avec classe erreur si besoin -->
                <div class="form-group <?= isset($erreurs['nom']) ? 'error' : '' ?>">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= $valeurs['nom'] ?>" placeholder="Ton nom" autocomplete="family-name">

                    <!-- Affiche l'erreur du nom si elle existe -->
                    <?php if (isset($erreurs['nom'])): ?><span class="form-error"><?= $erreurs['nom'] ?></span><?php endif; ?>
                </div>
            </div>

            <!-- Groupe du champ email, avec classe erreur si besoin -->
            <div class="form-group <?= isset($erreurs['email']) ? 'error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $valeurs['email'] ?>" placeholder="ton@email.com" autocomplete="email">

                <!-- Affiche l'erreur de l'email si elle existe -->
                <?php if (isset($erreurs['email'])): ?><span class="form-error"><?= $erreurs['email'] ?></span><?php endif; ?>
            </div>

            <!-- Groupe du champ mot de passe, avec classe erreur si besoin -->
            <div class="form-group <?= isset($erreurs['mdp']) ? 'error' : '' ?>">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="6 caractères minimum" autocomplete="new-password">

                <!-- Affiche l'erreur du mot de passe si elle existe -->
                <?php if (isset($erreurs['mdp'])): ?><span class="form-error"><?= $erreurs['mdp'] ?></span><?php endif; ?>
            </div>

            <!-- Groupe du champ confirmation du mot de passe, avec classe erreur si besoin -->
            <div class="form-group <?= isset($erreurs['mdp_conf']) ? 'error' : '' ?>">
                <label for="mdp_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="mdp_confirmation" name="mdp_confirmation" placeholder="Répète le mot de passe" autocomplete="new-password">

                <!-- Affiche l'erreur de confirmation si elle existe -->
                <?php if (isset($erreurs['mdp_conf'])): ?><span class="form-error"><?= $erreurs['mdp_conf'] ?></span><?php endif; ?>
            </div>

            <!-- Bouton pour envoyer le formulaire -->
            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>

        <!-- Lien vers la page de connexion -->
        <div class="auth-footer">
            Déjà un compte ? <a href="/connexion.php">Se connecter</a>
        </div>
    </div>
</div>

<?php
// Inclut le pied de page du site
include 'includes/footer.php';
?>
```