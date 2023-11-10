<?php
// Démarrez la session pour gérer l'authentification
session_start();

if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: process_php/connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <p>Photo envoyé avec succès !</p>

    <form action="../index.php" method="post">
        <input type="submit" value="Retour à l'accueil" id="deco_btn">
    </form>
    
</body>
</html>


