<?php
require_once(__DIR__ . '/../connect.php');

global $CONNEXION;

$CONNEXION = mysqli_connect(SERVEUR_BD, LOGIN_BD, PASS_BD, NOM_BD);

if (!$CONNEXION) {
    die("❌ Erreur de connexion MySQL : " . mysqli_connect_error());
}

mysqli_set_charset($CONNEXION, 'UTF8');
?>