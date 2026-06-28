<?php
require_once 'config/db.php';
require_once 'config/session.php';
require_once 'includes/fonctions.php';
requireConnexion();

if (!isset($_SESSION['resultat'])) redirect('/tableau_de_bord.php');

$r       = $_SESSION['resultat'];
$mention = getMention($r['note']);
unset($_SESSION['resultat']);

$page_title = 'Résultats';
include 'includes/header.php';
?>

<div class="resultats-page">

    <?php if ($r['invalide']): ?>
    <div class="alert alert-error alert-big">
        ⚠️ Cette tentative a été <strong>invalidée</strong> (anti-triche). Elle ne compte pas dans le classement.
    </div>
    <?php endif; ?>

    <div class="score-card">
        <div class="score-circle <?= $mention['class'] ?>">
            <span class="score-value"><?= $r['note'] ?></span>
            <span class="score-total">/20</span>
        </div>
        <div class="score-info">
            <h1><?= $r['cat_nom'] ?></h1>
            <div class="score-mention <?= $mention['class'] ?>"><?= $mention['label'] ?></div>
            <div class="score-meta">
                <span>✅ <?= $r['nb_bonnes'] ?> / <?= $r['nb_questions'] ?> bonnes réponses</span>
                <span>⏱ <?= formaterDuree($r['duree']) ?> utilisées</span>
            </div>
        </div>
    </div>

    <div class="resultats-actions">
        <a href="/lancer_qcm.php" class="btn-primary">🔄 Rejouer</a>
        <a href="/historique.php" class="btn-secondary">📋 Mon historique</a>
        <a href="/classement.php" class="btn-secondary">🏆 Classement</a>
    </div>

    <div class="recap-section">
        <h2>Récapitulatif détaillé</h2>
        <div class="recap-list">
            <?php foreach ($r['reponses_detail'] as $i => $rep): ?>
            <div class="recap-item <?= $rep['correcte'] ? 'correct' : 'incorrect' ?>">
                <div class="recap-num"><?= $i + 1 ?></div>
                <div class="recap-body">
                    <p class="recap-question"><?= htmlspecialchars($rep['question_texte']) ?></p>
                    <div class="recap-answers">
                        <div class="recap-user">
                            <span class="recap-icon"><?= $rep['correcte'] ? '✅' : '❌' ?></span>
                            <span>Ta réponse : <strong><?= htmlspecialchars($rep['reponse_utilisateur']) ?></strong></span>
                        </div>
                        <?php if (!$rep['correcte']): ?>
                        <div class="recap-correct">
                            <span class="recap-icon">💡</span>
                            <span>Bonne réponse : <strong><?= htmlspecialchars($rep['bonne_reponse']) ?></strong></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>