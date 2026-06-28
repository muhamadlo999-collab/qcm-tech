<?php
function requireConnexion() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: connexion.php');
        exit;
    }
}

function getMention($note) {
    if ($note >= 16) return ['label' => 'Excellent', 'class' => 'excellent'];
    if ($note >= 12) return ['label' => 'Bien', 'class' => 'bien'];
    if ($note >= 10) return ['label' => 'Passable', 'class' => 'passable'];
    return ['label' => 'Insuffisant', 'class' => 'insuffisant'];
}

function formaterDuree($secondes) {
    $min = floor($secondes / 60);
    $sec = $secondes % 60;
    return "{$min}min {$sec}s";
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>