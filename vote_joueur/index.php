<?php
// Inclure la connexion à la base de données
require_once('include/connexion.php');

// Vérifier si la connexion à la base est bien établie
if (!isset($CONNEXION) || !$CONNEXION) {
    die("❌ Erreur de connexion à la base de données.");
}

// Exécuter une requête SQL pour récupérer tous les joueurs
$result = $CONNEXION->query("SELECT * FROM joueurs");

// Vérifier si la requête a retourné des résultats
if (!$result) {
    die("❌ Erreur dans la requête SQL : " . $CONNEXION->error);
}

// Début de l'affichage HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votez pour le Meilleur Joueur</title>
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>

    <h1>Votez pour le meilleur joueur du monde ! 🌍⚽</h1>

    <form action="vote.php" method="POST">
        <div class="players-container">
            <?php
            // Boucle pour afficher chaque joueur
            while ($row = $result->fetch_assoc()) {
                echo "<div class='player-card'>";
                echo "<img src='" . $row['image'] . "' alt='" . $row['nom'] . "' width='150'><br>";
                echo "<strong>" . $row['nom'] . "</strong> (" . $row['equipe'] . ")<br>";
                echo "<button type='submit' name='joueur_id' value='" . $row['id'] . "'>Voter</button>";
                echo "</div>";
            }
            ?>
        </div>
    </form>

</body>
</html>