<?php
require_once('include/connexion.php'); // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars($_POST['nom']); // Sécurisation du nom
    $email = htmlspecialchars($_POST['email']); // Sécurisation de l'email
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérifier si l'email existe déjà dans la base de données
    $check_user = $CONNEXION->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check_user->bind_param("s", $email);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        echo "<h2>❌ Cet email est déjà utilisé !</h2>";
    } else {
        // Insérer le nouvel utilisateur
        $stmt = $CONNEXION->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $email, $mot_de_passe);

        if ($stmt->execute()) {
            echo "<h2>✅ Inscription réussie !</h2>";
            header("Location: login.php"); // Rediriger vers la page de connexion après inscription
            exit();
        } else {
            echo "<h2>❌ Erreur lors de l'inscription.</h2>";
        }
        $stmt->close();
    }
    $check_user->close();
}
?>

<h1>Inscription</h1>
<form method="POST">
    <input type="text" name="nom" placeholder="Votre Nom" required>
    <input type="email" name="email" placeholder="Votre Email" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>

<a href="login.php">Déjà inscrit ? Connectez-vous ici.</a>