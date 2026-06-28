<?php
// Inclut le fichier de connexion à la base de données
require_once ("config/db.php");

// Inclut le fichier qui gère les sessions
require_once ("config/session.php");

// Inclut le fichier contenant les fonctions utiles
require_once ("includes/fonctions.php");

// Récupère toutes les catégories depuis la base de données
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

// Récupère quelques statistiques du site
$stats = $pdo->query('
    SELECT
        (SELECT COUNT(*) FROM utilisateurs WHERE role = "user") AS nb_joueurs,
        (SELECT COUNT(*) FROM questions) AS nb_questions,
        (SELECT COUNT(*) FROM tentatives WHERE invalide = 0) AS nb_parties
')->fetch();

// Définit le titre de la page
$page_title = 'Accueil';

// Inclut l'en-tête du site
include 'includes/header.php';
?>

<!-- Section principale de la page d'accueil -->
<section class="hero">
    <!-- Contenu texte de la section principale -->
    <div class="hero-content">
        <!-- Petit badge de présentation -->
        <div class="hero-badge">⚡ Quiz Informatique</div>

        <!-- Titre principal -->
        <h1 class="hero-title">Teste tes connaissances<br><span class="gradient-text">en développement web</span></h1>

        <!-- Sous-titre de présentation -->
        <p class="hero-subtitle">8 catégories · 100 questions · 10 minutes par QCM · Note sur 20</p>

        <!-- Boutons d'action -->
        <div class="hero-actions">
            <!-- Si l'utilisateur est connecté -->
            <?php if (estConnecte()): ?>
                <!-- Bouton pour lancer un QCM -->
                <a href="/lancer_qcm.php" class="btn-hero-primary">Lancer un QCM →</a>

                <!-- Bouton pour voir le classement -->
                <a href="/classement.php" class="btn-hero-secondary">Voir le classement</a>
            <?php else: ?>
                <!-- Bouton pour s'inscrire -->
                <a href="/inscription.php" class="btn-hero-primary">Commencer gratuitement →</a>

                <!-- Bouton pour se connecter -->
                <a href="/connexion.php" class="btn-hero-secondary">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Partie visuelle de la section principale -->
    <div class="hero-visual">
        <!-- Carte flottante avec un exemple de question -->
        <div class="hero-card-float">
            <!-- Ligne contenant la question -->
            <div class="hc-row"><span class="hc-q">Que signifie HTTP ?</span></div>

            <!-- Réponses proposées -->
            <div class="hc-answers">
                <!-- Bonne réponse -->
                <div class="hc-ans correct">✓ HyperText Transfer Protocol</div>

                <!-- Mauvaise réponse -->
                <div class="hc-ans">High Tech Tool Protocol</div>

                <!-- Mauvaise réponse -->
                <div class="hc-ans">Host Terminal Transfer Path</div>
            </div>
        </div>
    </div>
</section>

<!-- Section des statistiques -->
<section class="stats-bar">
    <!-- Conteneur des statistiques -->
    <div class="stats-container">
        <!-- Statistique du nombre de joueurs -->
        <div class="stat-item">
            <!-- Nombre de joueurs -->
            <span class="stat-number"><?= number_format($stats['nb_joueurs']) ?></span>

            <!-- Texte de la statistique -->
            <span class="stat-label">Joueurs</span>
        </div>

        <!-- Séparateur entre deux statistiques -->
        <div class="stat-divider"></div>

        <!-- Statistique du nombre de questions -->
        <div class="stat-item">
            <!-- Nombre de questions -->
            <span class="stat-number"><?= $stats['nb_questions'] ?></span>

            <!-- Texte de la statistique -->
            <span class="stat-label">Questions</span>
        </div>

        <!-- Séparateur entre deux statistiques -->
        <div class="stat-divider"></div>

        <!-- Statistique du nombre de parties jouées -->
        <div class="stat-item">
            <!-- Nombre de parties jouées -->
            <span class="stat-number"><?= number_format($stats['nb_parties']) ?></span>

            <!-- Texte de la statistique -->
            <span class="stat-label">Parties jouées</span>
        </div>

        <!-- Séparateur entre deux statistiques -->
        <div class="stat-divider"></div>

        <!-- Statistique du nombre de catégories -->
        <div class="stat-item">
            <!-- Nombre de catégories -->
            <span class="stat-number">8</span>

            <!-- Texte de la statistique -->
            <span class="stat-label">Catégories</span>
        </div>
    </div>
</section>

<!-- Section contenant les catégories -->
<section class="categories-section">
    <!-- En-tête de la section -->
    <div class="section-header">
        <!-- Titre de la section -->
        <h2>Les catégories</h2>

        <!-- Petit texte de présentation -->
        <p>Choisis ta spécialité et affronte le quiz</p>
    </div>

    <!-- Grille des catégories -->
    <div class="categories-grid">
        <?php
        // Tableau qui associe chaque catégorie à une icône
        $icons = ['html'=>'🌐','css'=>'🎨','php'=>'🐘','sql'=>'🗄️','reseaux'=>'📡','algo'=>'🧮','sys'=>'💻','culture'=>'💡'];

        // Tableau qui associe chaque catégorie à une couleur CSS
        $colors = ['html'=>'cat-orange','css'=>'cat-blue','php'=>'cat-purple','sql'=>'cat-green','reseaux'=>'cat-red','algo'=>'cat-yellow','sys'=>'cat-teal','culture'=>'cat-pink'];

        // Parcourt toutes les catégories
        foreach ($categories as $cat):
            // Récupère l'icône de la catégorie, ou une icône par défaut
            $icon = $icons[$cat['slug']] ?? '📚';

            // Récupère la couleur de la catégorie, ou une couleur par défaut
            $color = $colors[$cat['slug']] ?? 'cat-blue';
        ?>
        <!-- Carte d'une catégorie -->
        <div class="cat-card <?= $color ?>">
            <!-- Icône de la catégorie -->
            <div class="cat-icon"><?= $icon ?></div>

            <!-- Informations de la catégorie -->
            <div class="cat-info">
                <!-- Nom de la catégorie -->
                <h3><?= htmlspecialchars($cat['nom']) ?></h3>

                <!-- Nombre de questions et durée -->
                <div class="cat-meta">
                    <span><?= $cat['nb_questions_qcm'] ?> questions</span>
                    <span>·</span>
                    <span><?= $cat['duree_minutes'] ?> min</span>
                </div>
            </div>

            <!-- Flèche décorative -->
            <div class="cat-arrow">→</div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
// Inclut le pied de page du site
include ("includes/footer.php");
?>
```