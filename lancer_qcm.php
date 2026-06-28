<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/fonction.php';

requireConnexion();

$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();
$icons = ['html'=>'🌐','css'=>'🎨','php'=>'🐘','sql'=>'🗄️','reseaux'=>'📡','algo'=>'🧮','sys'=>'💻','culture'=>'💡'];

$page_title = 'Lancer un QCM';
$extra_css = 'assets/css/qcm.css';

include __DIR__ . '/includes/header.php';
?>

<div class="launch-page">
    <div class="launch-header">
        <h1>Choisir une catégorie</h1>
        <p>Sélectionne le thème que tu veux réviser</p>
    </div>

    <form method="POST" action="qcm.php" id="form-launch">
        <div class="launch-grid">
            <?php foreach ($categories as $cat):
                $icon = $icons[$cat['slug']] ?? '📚';
            ?>
            <label class="launch-card" for="cat_<?= $cat['id'] ?>">
                <input type="radio" name="categorie_id" id="cat_<?= $cat['id'] ?>" value="<?= $cat['id'] ?>" required>
                <div class="lc-body">
                    <div class="lc-icon"><?= $icon ?></div>
                    <div class="lc-info">
                        <h3><?= htmlspecialchars($cat['nom']) ?></h3>
                        <div class="lc-meta">
                            <span>📝 <?= $cat['nb_questions_qcm'] ?> questions</span>
                            <span>⏱ <?= $cat['duree_minutes'] ?> min</span>
                        </div>
                    </div>
                    <div class="lc-check">✓</div>
                </div>
            </label>
            <?php endforeach; ?>
        </div>

        <div class="launch-rules">
            <h2>📋 Règles du QCM</h2>
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

        <div class="launch-action">
            <button type="submit" class="btn-launch" id="btn-launch">
                ⚡ Démarrer le QCM en plein écran
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('form-launch').addEventListener('submit', function(e) {
    const selected = document.querySelector('input[name="categorie_id"]:checked');
    if (!selected) {
        e.preventDefault();
        alert('Veuillez sélectionner une catégorie.');
        return;
    }
    const el = document.documentElement;
    if (el.requestFullscreen) el.requestFullscreen();
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>