<?php
function nettoyerInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function calculerNote($nb_bonnes, $nb_questions) {
    if ($nb_questions == 0) return 0;
    return round(($nb_bonnes / $nb_questions) * 20, 2);
}

function formaterDate($date) {
    $d = new DateTime($date);
    return $d->format('d/m/Y à H:i');
}

function formaterDuree($secondes) {
    $min = floor($secondes / 60);
    $sec = $secondes % 60;
    return sprintf('%02d:%02d', $min, $sec);
}

function getMention($note) {
    if ($note >= 16) return ['label' => 'Excellent', 'class' => 'mention-excellent'];
    if ($note >= 12) return ['label' => 'Bien', 'class' => 'mention-bien'];
    if ($note >= 10) return ['label' => 'Passable', 'class' => 'mention-passable'];
    return ['label' => 'À revoir', 'class' => 'mention-insuffisant'];
}

function getCategorieById($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}