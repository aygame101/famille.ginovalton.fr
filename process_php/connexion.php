<?php
// Démarrez la session pour gérer l'authentification
session_start();

require_once('europe.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Effectuez ici la vérification des informations de connexion avec la base de données.
    $identifiant = $_POST["identifiant"];
    $password = $_POST["password"];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Requête SQL pour obtenir les informations de l'utilisateur en fonction de l'identifiant
    $sql = "SELECT id, identifiant, password, salt, activated FROM users WHERE identifiant = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifiez si le compte est activé
        if ($user['activated'] == 1) {
            // Comparaison du mot de passe saisi avec le mot de passe haché dans la base de données
            $hashedPassword = hash('sha256', $password . $user['salt']);
            
            if ($hashedPassword === $user['password']) {
                // Informations de connexion valides
                $_SESSION['user_id'] = $user['id']; // Vous pouvez stocker l'ID de l'utilisateur dans la session
                header("Location: ../index.php");
                exit;
            } else {
                $message = "Identifiant ou mot de passe incorrect.";
            }
        } else {
            $message = "Votre compte est désactivé. Veuillez contacter l'administrateur.";
        }
    } else {
        $message = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../css/styles_index_conn.css">

    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    <form action="connexion.php" method="post">
        <label for="identifiant">Identifiant :</label>
        <input type="text" name="identifiant" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Se connecter">
    </form>

    <footer>
        <p class="footer_index_un"><a class="a_frst_line" href="../admin/index.php">Admin</a> - <a class="a_frst_line" href="https://github.com/aygame101/famille.gv.fr/">Github</a></p>
        <p class="footer_index">Created with <span style="color: #ff0000">&hearts;</span> by <a id="a_footer" href="https://ginovalton.fr">ginovalton.fr</a></p>
        <p class="footer_index">Take care.</p>
    </footer>
    
</body>
</html>
