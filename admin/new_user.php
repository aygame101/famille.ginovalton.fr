<?php
// require_once('jupiter.php');
require_once('../process_php/europe.php');


// Assurez-vous d'avoir une connexion PDO à MySQL établie ici.
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", "$username", "$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST["identifiant"];
    $password = $_POST["password"];

    // Génération d'un sel aléatoire
    $salt = bin2hex(random_bytes(16)); // Longueur recommandée : au moins 16 octets (128 bits)

    // Hashage sécurisé du mot de passe avec bcrypt
    $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

    // Date de création actuelle
    $creationDate = date('Y-m-d H:i:s');

    // Insertion des données dans la table "users" avec "activated" par défaut à 1
    $sql = "INSERT INTO users (identifiant, password, salt, date_creation, activated) VALUES (?, ?, ?, ?, 1)";
    
    // Utilisation de requêtes préparées avec PDO pour éviter les injections SQL
    $stmt = $pdo->prepare($sql);

    // Protection CSRF : Vérification du jeton CSRF
    if ($_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("Erreur CSRF : Demande non autorisée.");
    }

    try {
        $stmt->execute([$identifiant, $hashedPassword, $salt, $creationDate]);
        echo "Utilisateur créé avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
    }
}
?>
