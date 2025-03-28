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

// Vérifie s’il a déjà voté pour ce joueur
$check = $CONNEXION->prepare("SELECT id FROM votes WHERE joueur_id = ? AND (utilisateur_id = ? OR ip_votant = ?)");
$check->bind_param("iis", $joueur_id, $utilisateur_id, $ip);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "⚠️ Vous avez déjà voté pour ce joueur.";
    exit();
}
$check->close();

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