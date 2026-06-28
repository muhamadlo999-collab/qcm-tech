<?php
// Définit l'adresse du serveur de base de données
define('DB_HOST', 'localhost');

// Définit le nom de la base de données
define('DB_NAME', 'qcm_tech');

// Définit le nom d'utilisateur MySQL
define('DB_USER', 'root');

// Définit le mot de passe MySQL
define('DB_PASS', '');

try {
    // Crée une connexion à la base de données avec PDO
    $pdo = new PDO(
        // Indique le serveur, le nom de la base et l'encodage utilisé
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',

        // Utilise le nom d'utilisateur défini plus haut
        DB_USER,

        // Utilise le mot de passe défini plus haut
        DB_PASS,

        [
            // Active l'affichage des erreurs sous forme d'exceptions
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Récupère les résultats sous forme de tableau associatif
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Désactive l'émulation des requêtes préparées
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Affiche un message d'erreur si la connexion échoue
    die(json_encode(['erreur' => 'Connexion impossible : ' . $e->getMessage()]));
}