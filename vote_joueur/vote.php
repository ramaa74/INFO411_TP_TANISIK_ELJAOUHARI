<?php
require_once('include/connexion.php'); // Connexion à la base

// Vérifier si le formulaire a été soumis et si un joueur a été sélectionné
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['joueur_id'])) {
    $joueur_id = intval($_POST['joueur_id']); // Sécuriser l'ID du joueur
    $ip_votant = $_SERVER['REMOTE_ADDR']; // Récupérer l'IP de l'utilisateur pour éviter les votes multiples

    // Vérifier si cet utilisateur a déjà voté
    $check_vote = $CONNEXION->prepare("SELECT id FROM votes WHERE ip_votant = ?");
    $check_vote->bind_param("s", $ip_votant);
    $check_vote->execute();
    $check_vote->store_result();

    if ($check_vote->num_rows > 0) {
        // L'utilisateur a déjà voté, afficher un message
        echo "<h2>❌ Vous avez déjà voté !</h2>";
    } else {
        // Enregistrer le vote dans la base de données
        $stmt = $CONNEXION->prepare("INSERT INTO votes (joueur_id, ip_votant) VALUES (?, ?)");
        $stmt->bind_param("is", $joueur_id, $ip_votant);

        if ($stmt->execute()) {
            echo "<h2>✅ Vote enregistré avec succès !</h2>";
        } else {
            echo "<h2>❌ Erreur lors du vote.</h2>";
        }
        $stmt->close();
    }

    $check_vote->close();
} else {
    echo "<h2>❌ Aucune sélection de joueur.</h2>";
}
?>

<a href="index.php">Retour à l'accueil</a>