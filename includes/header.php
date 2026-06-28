<?php
// Inclut le fichier qui gère les sessions
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/fonction.php';

// Récupère le nom du fichier de la page actuelle
$page_actuelle = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l'encodage des caractères -->
    <meta charset="UTF-8">

    <!-- Rend la page adaptée aux écrans mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Définit le titre de la page -->
    <title><?= isset($page_title) ? $page_title . ' — QCM Tech' : 'QCM Tech' ?></title>

    <!-- Charge le fichier CSS principal -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Vérifie si un fichier CSS supplémentaire existe -->
    <?php if (isset($extra_css)): ?>
        <!-- Charge le fichier CSS supplémentaire -->
        <link rel="stylesheet" href="<?= $extra_css ?>">
    <?php endif; ?>
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar">
    <!-- Conteneur de la navigation -->
    <div class="nav-container">
        <!-- Logo du site, avec lien différent selon la connexion -->
        <a href="<?= estConnecte() ? '/tableau_de_bord.php' : '/index.php' ?>" class="nav-logo">
            <!-- Icône du logo -->
            <span class="logo-icon">la place de logo</span>

            <!-- Texte du logo -->
            <span class="logo-text">QCM<strong>Tech</strong></span>
        </a>

        <!-- Si l'utilisateur est connecté -->
        <?php if (estConnecte()): ?>
            <!-- Liens de navigation pour utilisateur connecté -->
            <div class="nav-links">
                <!-- Lien vers la page pour jouer -->
                <a href="/lancer_qcm.php" class="nav-link <?= $page_actuelle === 'lancer_qcm.php' ? 'active' : '' ?>">Jouer</a>

                <!-- Lien vers l'historique -->
                <a href="/historique.php" class="nav-link <?= $page_actuelle === 'historique.php' ? 'active' : '' ?>">Historique</a>

                <!-- Lien vers le classement -->
                <a href="/classement.php" class="nav-link <?= $page_actuelle === 'classement.php' ? 'active' : '' ?>">Classement</a>

                <!-- Si l'utilisateur est administrateur -->
                <?php if (estAdmin()): ?>
                    <!-- Lien vers l'espace administrateur -->
                    <a href="/admin/index.php" class="nav-link nav-admin">Admin</a>
                <?php endif; ?>
            </div>

            <!-- Zone affichant l'utilisateur connecté -->
            <div class="nav-user">
                <!-- Affiche le prénom de l'utilisateur -->
                <span class="nav-username">👤 <?= nettoyerInput($_SESSION['prenom']) ?></span>

                <!-- Lien pour se déconnecter -->
                <a href="/logout.php" class="btn-logout">Déconnexion</a>
            </div>
        <?php else: ?>
            <!-- Liens de navigation pour visiteur non connecté -->
            <div class="nav-links">
                <!-- Lien vers la page de connexion -->
                <a href="/connexion.php" class="nav-link <?= $page_actuelle === 'connexion.php' ? 'active' : '' ?>">Connexion</a>

                <!-- Lien vers la page d'inscription -->
                <a href="inscription.php" class="btn-primary <?= $page_actuelle === 'inscription.php' ? 'active' : '' ?>">S'inscrire</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<!-- Contenu principal de la page -->
<main class="main-content">