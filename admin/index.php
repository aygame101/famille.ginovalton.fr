<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connected'])) {
    // Rediriger vers la page de connexion
    header('Location: connexion.php');
    exit();
}

// Déconnecter automatiquement après 5 minutes d'inactivité
// $SESSION['$timeout'] = 300; // 5 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['$timeout'])) {
    // Détruire toutes les variables de session et déconnecter l'utilisateur
    session_unset();
    session_destroy();

    // Rediriger vers la page de connexion
    header('Location: connexion.php');
    exit();
}
header("Content-Type: text/html;charset=UTF-8");
// Mettre à jour le timestamp de dernière activité
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace Admin</title>
</head>
<body>
    <h1>Création d'utilisateur</h1>
    <form action="new_user.php" method="post">
        <label for="user">Nom d'utilisateur :</label>
        <input type="text" name="identifiant" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Créer l'utilisateur">
    </form>

    <form action="deconnexion.php" method="post">
        <input type="submit" value="Déconnexion" id="deco_btn">
    </form>
</body>
</html>
