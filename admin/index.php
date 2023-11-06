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
</body>
</html>
