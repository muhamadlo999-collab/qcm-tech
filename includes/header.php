<?php
// Inclut le fichier qui gère les sessions
require_once __DIR__ . '/../config/session.php';

// Récupère le nom du fichier de la page actuelle
$page_actuelle = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= isset($page_title) ? $page_title . ' — QCM Tech' : 'QCM Tech' ?></title>

    <link rel="stylesheet" href="assets/css/style.css">

    <?php if (isset($extra_css)): ?>
        <link rel="stylesheet" href="<?= ltrim($extra_css, '/') ?>">
    <?php endif; ?>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="<?= estConnecte() ? 'tableau_de_bord.php' : 'index.php' ?>" class="nav-logo">
            <span class="logo-icon">⚡</span>

            <span class="logo-text">QCM<strong>Tech</strong></span>
        </a>

        <?php if (estConnecte()): ?>
            <div class="nav-links">
                <a href="lancer_qcm.php" class="nav-link <?= $page_actuelle === 'lancer_qcm.php' ? 'active' : '' ?>">Jouer</a>
                <a href="historique.php" class="nav-link <?= $page_actuelle === 'historique.php' ? 'active' : '' ?>">Historique</a>
                <a href="classement.php" class="nav-link <?= $page_actuelle === 'classement.php' ? 'active' : '' ?>">Classement</a>

                <?php if (estAdmin()): ?>
                    <a href="admin/index.php" class="nav-link nav-admin">Admin</a>
                <?php endif; ?>
            </div>

            <div class="nav-user">
                <span class="nav-username">👤 <?= nettoyerInput($_SESSION['prenom']) ?></span>
                <a href="logout.php" class="btn-logout">Déconnexion</a>
            </div>
        <?php else: ?>
            <div class="nav-links">
                <a href="connexion.php" class="nav-link <?= $page_actuelle === 'connexion.php' ? 'active' : '' ?>">Connexion</a>
                <a href="inscription.php" class="btn-primary <?= $page_actuelle === 'inscription.php' ? 'active' : '' ?>">S'inscrire</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="main-content">