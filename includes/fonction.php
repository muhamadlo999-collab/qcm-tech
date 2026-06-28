Fonction 
<?php
// Fonction qui nettoie une donnée saisie par l'utilisateur
function nettoyerInput($data) {
    // Supprime les espaces, les balises HTML et protège les caractères spéciaux
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Fonction qui calcule une note sur 20
function calculerNote($nb_bonnes, $nb_questions) {
    // Si le nombre de questions est 0, on retourne 0 pour éviter une division par zéro
    if ($nb_questions == 0) return 0;

    // Calcule la note sur 20 et garde 2 chiffres après la virgule
    return round(($nb_bonnes / $nb_questions) * 20, 2);
}

// Fonction qui formate une date
function formaterDate($date) {
    // Crée un objet DateTime avec la date donnée
    $d = new DateTime($date);

    // Retourne la date au format jour/mois/année heure:minute
    return $d->format('d/m/Y à H:i');
}

// Fonction qui formate une durée en minutes et secondes
function formaterDuree($secondes) {
    // Calcule le nombre de minutes
    $min = floor($secondes / 60);

    // Calcule les secondes restantes
    $sec = $secondes % 60;

    // Retourne la durée au format 00:00
    return sprintf('%02d:%02d', $min, $sec);
}

// Fonction qui retourne une mention selon la note
function getMention($note) {
    // Si la note est supérieure ou égale à 16
    if ($note >= 16) return ['label' => 'Excellent', 'class' => 'mention-excellent'];

    // Si la note est supérieure ou égale à 12
    if ($note >= 12) return ['label' => 'Bien', 'class' => 'mention-bien'];

    // Si la note est supérieure ou égale à 10
    if ($note >= 10) return ['label' => 'Passable', 'class' => 'mention-passable'];

    // Sinon, la note est insuffisante
    return ['label' => 'À revoir', 'class' => 'mention-insuffisant'];
}

// Fonction qui récupère une catégorie grâce à son identifiant
function getCategorieById($pdo, $id) {
    // Prépare la requête SQL pour chercher la catégorie
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');

    // Exécute la requête avec l'identifiant donné
    $stmt->execute([$id]);

    // Retourne la catégorie trouvée
    return $stmt->fetch();
}

// Fonction qui redirige vers une autre page
function redirect($url) {
    // Envoie une redirection vers l'URL donnée
    header('Location: ' . $url);

    // Arrête l'exécution du script
    exit;
}
