
<?php
// Inclut le fichier de connexion à la base de données
require_once 'config/db.php';

// Inclut le fichier qui gère les sessions
require_once 'config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once 'includes/fonctions.php';

// Oblige l'utilisateur à être connecté
requireConnexion();

// Récupère toutes les catégories depuis la base de données
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

// Associe chaque catégorie à une icône
$icons = ['html'=>'🌐','css'=>'🎨','php'=>'🐘','sql'=>'🗄️','reseaux'=>'📡','algo'=>'🧮','sys'=>'💻','culture'=>'💡'];

// Définit le titre de la page
$page_title = 'Lancer un QCM';

// Ajoute un fichier CSS spécial pour le QCM
$extra_css = '/assets/css/qcm.css';

// Inclut l'en-tête du site
include 'includes/header.php';
?>

<div class="launch-page">
    <!-- En-tête de la page de lancement -->
    <div class="launch-header">
        <!-- Titre principal -->
        <h1>Choisir une catégorie</h1>

        <!-- Petit texte d'aide -->
        <p>Sélectionne le thème que tu veux réviser</p>
    </div>

    <!-- Formulaire qui envoie vers la page du QCM -->
    <form method="POST" action="/qcm.php" id="form-launch">
        <!-- Grille des catégories -->
        <div class="launch-grid">
            <!-- Parcourt toutes les catégories -->
            <?php foreach ($categories as $cat):
                // Récupère l'icône de la catégorie, ou une icône par défaut
                $icon = $icons[$cat['slug']] ?? '📚';
            ?>
            <!-- Carte cliquable pour choisir une catégorie -->
            <label class="launch-card" for="cat_<?= $cat['id'] ?>">
                <!-- Bouton radio caché pour sélectionner la catégorie -->
                <input type="radio" name="categorie_id" id="cat_<?= $cat['id'] ?>" value="<?= $cat['id'] ?>" required>

                <!-- Contenu de la carte -->
                <div class="lc-body">
                    <!-- Icône de la catégorie -->
                    <div class="lc-icon"><?= $icon ?></div>

                    <!-- Informations de la catégorie -->
                    <div class="lc-info">
                        <!-- Nom de la catégorie -->
                        <h3><?= htmlspecialchars($cat['nom']) ?></h3>

                        <!-- Nombre de questions et durée -->
                        <div class="lc-meta">
                            <span>📝 <?= $cat['nb_questions_qcm'] ?> questions</span>
                            <span>⏱ <?= $cat['duree_minutes'] ?> min</span>
                        </div>
                    </div>

                    <!-- Symbole affiché quand la catégorie est sélectionnée -->
                    <div class="lc-check">✓</div>
                </div>
            </label>
            <?php endforeach; ?>
        </div>

        <!-- Bloc des règles du QCM -->
        <div class="launch-rules">
            <!-- Titre des règles -->
            <h2>📋 Règles du QCM</h2>

            <!-- Liste des règles -->
            <ul class="rules-list">
                <li>✅ <strong>10 questions</strong> tirées aléatoirement (5 pour Culture informatique)</li>
                <li>✅ <strong>4 réponses</strong> proposées, une seule est correcte</li>
                <li>✅ <strong>10 minutes</strong> maximum pour répondre</li>
                <li>✅ <strong>Note sur 20</strong> calculée automatiquement</li>
                <li>⚠️ Le QCM se lance en <strong>plein écran obligatoire</strong></li>
                <li>⚠️ Quitter le plein écran ou changer d'onglet <strong>invalide la tentative</strong></li>
                <li>⚠️ Le clic droit et le copier/coller sont <strong>désactivés</strong></li>
            </ul>
        </div>

        <!-- Zone du bouton de lancement -->
        <div class="launch-action">
            <!-- Bouton pour démarrer le QCM -->
            <button type="submit" class="btn-launch" id="btn-launch">
                ⚡ Démarrer le QCM en plein écran
            </button>
        </div>
    </form>
</div>

<script>
// Ajoute une action quand le formulaire est envoyé
document.getElementById('form-launch').addEventListener('submit', function(e) {
    // Cherche la catégorie sélectionnée
    const selected = document.querySelector('input[name="categorie_id"]:checked');

    // Si aucune catégorie n'est sélectionnée
    if (!selected) {
        // Empêche l'envoi du formulaire
        e.preventDefault();

        // Affiche un message d'alerte
        alert('Veuillez sélectionner une catégorie.');

        // Arrête la fonction
        return;
    }

    // Récupère l'élément principal de la page
    const el = document.documentElement;

    // Active le plein écran si le navigateur le permet
    if (el.requestFullscreen) el.requestFullscreen();

    // Active le plein écran pour certains navigateurs comme Safari
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
});
</script>

<?php
// Inclut le pied de page du site
include 'includes/footer.php';
?>
```