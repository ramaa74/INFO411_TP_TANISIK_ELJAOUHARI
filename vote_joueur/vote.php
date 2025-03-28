<?php
session_start();
require_once('include/connexion.php');

// Vérifie qu'un joueur a été sélectionné
if (!isset($_POST['joueur_id'])) {
    echo "❌ Aucun joueur sélectionné.";
    exit();
}

$joueur_id = intval($_POST['joueur_id']); // Sécurisation de l'ID joueur
$ip = $_SERVER['REMOTE_ADDR'];            // Récupère l'adresse IP du votant
$utilisateur_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // User connecté ou non

// Requête préparée pour insérer le vote
$requete = $CONNEXION->prepare("INSERT INTO votes (joueur_id, ip_votant, date_vote, utilisateur_id) VALUES (?, ?, NOW(), ?)");
$requete->bind_param("isi", $joueur_id, $ip, $utilisateur_id);

if ($requete->execute()) {
    echo "✅ Merci pour votre vote !";
    header("Location: resultats.php");
    exit();
} else {
    echo "❌ Une erreur est survenue lors du vote.";
}

$requete->close();
?>