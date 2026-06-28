<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estConnecte() {
    return isset($_SESSION['user_id']);
}

function estAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireConnexion() {
    if (!estConnecte()) {
        header('Location: /connexion.php');
        exit;
    }
    if (isset($_SESSION['bloque']) && $_SESSION['bloque'] == 1) {
        session_destroy();
        header('Location: /connexion.php?erreur=bloque');
        exit;
    }
}

function requireAdmin() {
    requireConnexion();
    if (!estAdmin()) {
        header('Location: /tableau_de_bord.php');
        exit;
    }
}
