<?php
require('connect.php');

$CONNEXION = mysqli_connect(SERVEUR_BD, LOGIN_BD, PASS_BD, NOM_BD);

if (!$CONNEXION) {
    die("❌ Erreur de connexion : " . mysqli_connect_error());
}

echo "<h2>✅ Connexion réussie à la base de données !</h2>";
?>