<?php
// Vérifie si aucune session n'est encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    // Démarre une nouvelle session
    session_start();
}

// Fonction qui vérifie si l'utilisateur est connecté
function estConnecte() {
    // Retourne vrai si l'identifiant de l'utilisateur existe dans la session
    return isset($_SESSION['user_id']);
}

// Fonction qui vérifie si l'utilisateur est administrateur
function estAdmin() {
    // Retourne vrai si le rôle existe et vaut "admin"
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction qui oblige l'utilisateur à être connecté
function requireConnexion() {
    // Si l'utilisateur n'est pas connecté
    if (!estConnecte()) {
        // Redirige vers la page de connexion
        header('Location: /connexion.php');

        // Arrête l'exécution du script
        exit;
    }

    // Vérifie si l'utilisateur est bloqué
    if (isset($_SESSION['bloque']) && $_SESSION['bloque'] == 1) {
        // Détruit la session de l'utilisateur
        session_destroy();

        // Redirige vers la connexion avec un message d'erreur
        header('Location: /connexion.php?erreur=bloque');

        // Arrête l'exécution du script
        exit;
    }
}

// Fonction qui oblige l'utilisateur à être administrateur
function requireAdmin() {
    // Vérifie d'abord que l'utilisateur est connecté
    requireConnexion();

    // Si l'utilisateur n'est pas administrateur
    if (!estAdmin()) {
        // Redirige vers le tableau de bord
        header('Location: /tableau_de_bord.php');

        // Arrête l'exécution du script
        exit;
    }
}