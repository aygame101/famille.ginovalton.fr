<!DOCTYPE html>
<html>
<head>
    <title>Espace Admin</title>
</head>
<body>
    <h1>Création d'utilisateur</h1>
    <form action="process.php" method="post">
        <label for="user">Nom d'utilisateur :</label>
        <input type="text" name="user" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Créer l'utilisateur">
    </form>
</body>
</html>
