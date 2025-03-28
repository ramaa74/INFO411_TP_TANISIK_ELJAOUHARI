<?php
session_start();
require_once('include/connexion.php'); // Connexion base de données

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête infos de l'utilisateur
    $stmt = $CONNEXION->prepare("SELECT id, nom, mot_de_passe, role FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nom, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($mot_de_passe, $hashed_password)) {
        // Connexion réussie, on stocke les infos dans la session
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $nom;
        $_SESSION['user_role'] = $role;

        // Redirection la page d'accueil
        header("Location: index.php");
        exit();
    } else {
        $erreur = "❌ Email ou mot de passe incorrect.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="main-container">
        <h1>Connexion</h1>

        <?php if (isset($erreur)) : ?>
            <p style="color: red;"><?= $erreur ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Votre email" required><br><br>
            <input type="password" name="mot_de_passe" placeholder="Votre mot de passe" required><br><br>
            <button type="submit">Se connecter</button>
        </form>

        <p>Pas encore de compte ? <a href="register.php">S'inscrire ici</a></p>
    </div>

</body>
</html>