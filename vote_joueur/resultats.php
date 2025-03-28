<?php
require_once('include/connexion.php'); // Connexion à la base

// Requête SQL pour récupérer les votes
$result = $CONNEXION->query("
    SELECT joueurs.nom, COUNT(votes.id) AS total_votes
    FROM joueurs
    LEFT JOIN votes ON joueurs.id = votes.joueur_id
    GROUP BY joueurs.id
    ORDER BY total_votes DESC
");

// Stocker les données pour Chart.js
$joueurs = [];
$votes = [];

while ($row = $result->fetch_assoc()) {
    $joueurs[] = $row['nom'];
    $votes[] = $row['total_votes'];
}

// Convertir les données en format JSON pour Chart.js
$joueurs_json = json_encode($joueurs);
$votes_json = json_encode($votes);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des Votes</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Import Chart.js -->
</head>
<body>

    <h1>🏆 Classement des joueurs</h1>

    <!-- Graphique -->
    <div class="chart-container">
        <canvas id="votesChart"></canvas>
    </div>

    <!-- Tableau des résultats (Optionnel) -->
    <table class="resultats-table">
        <thead>
            <tr>
                <th>Position</th>
                <th>Joueur</th>
                <th>Votes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $position = 1;
            $result->data_seek(0); // Remet le pointeur au début pour réutiliser les résultats
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>#" . $position . "</td>";
                echo "<td>" . $row['nom'] . "</td>";
                echo "<td>" . $row['total_votes'] . "</td>";
                echo "</tr>";
                $position++;
            }
            ?>
        </tbody>
    </table>

    <a href="index.php">🔙 Retour à l'accueil</a>

    <!-- Script pour afficher le graphique -->
    <script>
        const ctx = document.getElementById('votesChart').getContext('2d');
        const votesChart = new Chart(ctx, {
            type: 'bar', // Type de graphique : barres
            data: {
                labels: <?php echo $joueurs_json; ?>, // Noms des joueurs
                datasets: [{
                    label: 'Nombre de votes',
                    data: <?php echo $votes_json; ?>, // Nombre de votes
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>