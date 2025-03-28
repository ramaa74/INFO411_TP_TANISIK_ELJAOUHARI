<?php
session_start();
require_once('include/connexion.php');

if (!isset($_SESSION['user_id'])) {
    echo "<h2>⛔ Vous devez être connecté pour voir votre vote.</h2>";
    exit();
}

$user_id = $_SESSION['user_id'];

$requete = $CONNEXION->prepare("
    SELECT j.nom AS joueur_nom
    FROM votes v
    JOIN joueurs j ON v.joueur_id = j.id
    WHERE v.utilisateur_id = ?
    ORDER BY v.date_vote DESC
    LIMIT 1
");
$requete->bind_param("i", $user_id);
$requete->execute();
$requete->bind_result($joueur_nom);
$requete->fetch();
?>

<h1>🗳️ Mon vote</h1>
<?php if ($joueur_nom) : ?>
    <p>✅ Vous avez voté pour : <strong><?= htmlspecialchars($joueur_nom) ?></strong></p>
<?php else : ?>
    <p>⚠️ Vous n'avez pas encore voté.</p>
<?php endif; ?>

<a href="index.php">⬅ Retour</a>