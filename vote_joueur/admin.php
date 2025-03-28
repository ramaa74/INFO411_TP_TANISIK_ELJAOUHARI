<?php
session_start();
require_once('include/connexion.php');

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "<h2>⛔ Accès refusé. Page réservée aux administrateurs.</h2>";
    exit();
}

// afficher les votes 
$requete = $CONNEXION->query("
    SELECT votes.id, votes.ip_votant, votes.date_vote, joueurs.nom AS joueur_nom, utilisateurs.nom AS utilisateur_nom
    FROM votes
    LEFT JOIN joueurs ON votes.joueur_id = joueurs.id
    LEFT JOIN utilisateurs ON votes.utilisateur_id = utilisateurs.id
    ORDER BY votes.date_vote DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Votes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="main-container">
        <h1>🛠️ Panneau d'administration</h1>

        <table class="resultats-table">
            <thead>
                <tr>
                    <th>ID Vote</th>
                    <th>Utilisateur</th>
                    <th>Joueur voté</th>
                    <th>IP votant</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($vote = $requete->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $vote['id'] ?></td>
                        <td><?= htmlspecialchars($vote['utilisateur_nom'] ?? 'Anonyme') ?></td>
                        <td><?= htmlspecialchars($vote['joueur_nom']) ?></td>
                        <td><?= $vote['ip_votant'] ?></td>
                        <td><?= $vote['date_vote'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="index.php">⬅ Retour à l'accueil</a>
    </div>

</body>
</html>