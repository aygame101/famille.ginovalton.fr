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
    <form action="process_php/up_picture.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="photo">Photo :</label>
            <input type="file" name="photo" id="photo" accept=".png, .jpeg, .jpg" required>

            <label for="message">Message :</label>
            <input type="text" name="message" id="message" required>
        </div>
        <div>
            <button type="submit">Upload Photo</button>
        </div>
    </form>

    <form action="process_php/deconnexion.php" method="post">
        <input type="submit" value="Déconnexion" id="deco_btn">
    </form>

    <?php
    // Récupérez les photos et les informations associées depuis la base de données
    $sql = "SELECT * FROM photos
    INNER JOIN users ON photos.user_id = users.id"; // Vous pouvez ajuster cette requête selon vos besoins

    require_once('process_php/europe.php');

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", "$db_username", "$db_password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        echo '<h2>Photos Postées :</h2>';
        echo '<table>';
        echo '<tr><th>Photo</th><th>Message</th><th>Nom de l\'utilisateur</th></tr>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td><img src="' . $row['chemin'] . '" alt="Photo"></td>';
            echo '<td>' . $row['message'] . '</td>';
            echo '<td>' . $row['identifiant'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<h2>Aucune photo n\'a été postée.</h2>';
    }
    ?>


</body>
</html>


