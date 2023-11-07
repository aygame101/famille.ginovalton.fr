<?php
// Assurez-vous d'avoir une connexion PDO à MySQL établie ici.
session_start();

require_once('../process_php/europe.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['connected'])) {
    // Récupérer l'ID de l'utilisateur à réactiver depuis le formulaire
    $user_id = $_POST["user_id"];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Mettre à jour la valeur "activated" à 1 pour réactiver l'utilisateur
    $sql = "UPDATE users SET activated = 1 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);

    // Rediriger vers la page d'accueil
    header("Location: index.php");
    exit;
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit;
}
?>
