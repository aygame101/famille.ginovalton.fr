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

    <title>Accueil</title>
</head>
<body>
    <h1>Accueil</h1>
    <p>connected</p>
    <form action="process_php/up_picture.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="photo">Upload your photo:</label>
            <input type="file" name="photo" id="photo" accept=".png, .jpeg, .jpg, .heic" required>
        </div>
        <div>
            <button type="submit">Upload Photo</button>
        </div>
    </form>

    <form action="process_php/deconnexion.php" method="post">
        <input type="submit" value="Déconnexion" id="deco_btn">
    </form>
</body>
</html>


