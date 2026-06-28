
<?php
// Inclut le fichier de connexion à la base de données
require_once 'config/db.php';

// Inclut le fichier qui gère les sessions
require_once 'config/session.php';

// Inclut le fichier contenant les fonctions utiles
require_once 'includes/fonctions.php';

// Oblige l'utilisateur à être connecté
requireConnexion();

// Vérifie qu'un QCM est bien en cours et que le formulaire a été envoyé en POST
if (!isset($_SESSION['qcm']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirige vers la page de lancement du QCM
    redirect('/lancer_qcm.php');
}

// Récupère les informations du QCM stockées dans la session
$qcm = $_SESSION['qcm'];

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupère l'identifiant de la catégorie du QCM
$cat_id = $qcm['categorie_id'];

// Récupère les questions du QCM
$questions = $qcm['questions'];

// Récupère la date de début du QCM
$date_debut = $qcm['date_debut'];

// Calcule la durée maximale du QCM en secondes
$duree_max = $qcm['duree_minutes'] * 60;

// Récupère si la tentative est invalide ou non
$invalide = (int)($_POST['invalide'] ?? 0);

// Crée un objet DateTime pour l'heure actuelle
$now = new DateTime();

// Crée un objet DateTime pour la date de début
$debut = new DateTime($date_debut);

// Calcule la durée réelle passée sur le QCM
$duree_reelle = $now->getTimestamp() - $debut->getTimestamp();

// Si la durée réelle dépasse la durée maximale autorisée
if ($duree_reelle > $duree_max + 30) {
    // La tentative devient invalide
    $invalide = 1;
}

// Initialise le nombre de bonnes réponses
$nb_bonnes = 0;

// Tableau qui stockera le détail des réponses
$reponses_detail = [];

// Parcourt toutes les questions du QCM
foreach ($questions as $i => $q) {
    // Récupère la réponse choisie par l'utilisateur
    $rep_user = $_POST['reponse'][$i] ?? '';

    // Récupère la bonne réponse
    $bonne = $_POST['bonne_reponse'][$i] ?? '';

    // Vérifie si la réponse de l'utilisateur est correcte
    $correcte = ($rep_user === $bonne) ? 1 : 0;

    // Si la réponse est correcte, ajoute 1 au score
    if ($correcte) $nb_bonnes++;

    // Ajoute le détail de la réponse dans le tableau
    $reponses_detail[] = [
        'question_id' => $q['id'],
        'reponse_utilisateur' => $rep_user,
        'bonne_reponse' => $bonne,
        'correcte' => $correcte,
        'question_texte' => $q['question'],
    ];
}

// Récupère les informations de la catégorie
$cat = getCategorieById($pdo, $cat_id);

// Calcule la note sur 20
$note = calculerNote($nb_bonnes, $cat['nb_questions_qcm']);

// Récupère la date de fin du QCM
$date_fin = date('Y-m-d H:i:s');

// Prépare la requête pour enregistrer la tentative
$stmt = $pdo->prepare('
    INSERT INTO tentatives (utilisateur_id, categorie_id, note_sur_20, nb_bonnes, date_debut, date_fin, duree_secondes, invalide)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
');

// Enregistre la tentative dans la base de données
$stmt->execute([$user_id, $cat_id, $note, $nb_bonnes, $date_debut, $date_fin, $duree_reelle, $invalide]);

// Récupère l'identifiant de la tentative enregistrée
$tentative_id = $pdo->lastInsertId();

// Prépare la requête pour enregistrer les réponses
$stmt = $pdo->prepare('
    INSERT INTO reponses (tentative_id, question_id, reponse_utilisateur, correcte)
    VALUES (?, ?, ?, ?)
');

// Parcourt toutes les réponses détaillées
foreach ($reponses_detail as $r) {
    // Enregistre chaque réponse dans la base de données
    $stmt->execute([$tentative_id, $r['question_id'], $r['reponse_utilisateur'], $r['correcte']]);
}

// Si la tentative est valide
if (!$invalide) {
    // Met à jour le classement de l'utilisateur pour cette catégorie
    $pdo->prepare('
        INSERT INTO classement (utilisateur_id, categorie_id, meilleure_note, moyenne, nb_tentatives)
        VALUES (?, ?, ?, ?, 1)
        ON DUPLICATE KEY UPDATE
            meilleure_note = GREATEST(meilleure_note, VALUES(meilleure_note)),
            moyenne        = (moyenne * nb_tentatives + VALUES(meilleure_note)) / (nb_tentatives + 1),
            nb_tentatives  = nb_tentatives + 1
    ')->execute([$user_id, $cat_id, $note, $note]);
}

// Stocke les résultats du QCM dans la session
$_SESSION['resultat'] = [
    'tentative_id' => $tentative_id,
    'note' => $note,
    'nb_bonnes' => $nb_bonnes,
    'nb_questions' => $cat['nb_questions_qcm'],
    'invalide' => $invalide,
    'duree' => $duree_reelle,
    'cat_nom' => $cat['nom'],
    'reponses_detail' => $reponses_detail,
];

// Supprime le QCM en cours de la session
unset($_SESSION['qcm']);

// Redirige vers la page des résultats
redirect('/resultats.php');