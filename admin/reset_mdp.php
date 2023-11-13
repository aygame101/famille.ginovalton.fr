<!-- Formulaire admin pour reset mdp user -->

<?php
// Assurez-vous d'avoir une connexion PDO à MySQL établie ici.
session_start();

require_once('../process_php/europe.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['connected'])) {
    // Récupérer l'ID de l'utilisateur à réactiver depuis le formulaire
    $user_id = $_POST["identifiant"];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    $nouvmdp = "";

    // Formulaire ou l'admin peut entrer le nouveau mdp
    echo "<form action='reset_mdp.php' method='post'>";
    echo "<label for='nouvmdp'>Nouveau mot de passe :</label>";
    echo "<input type='password' name='nouvmdp' required><br>";
    echo "<input type='submit' value='Reset'>";
    echo "</form>";
    echo "<form action='index.php' method='post'>";
    echo "<input type='submit' value='Retour'>";

    // Récupérer le nouveau mdp
    $nouvmdp = $_POST["nouvmdp"];

    // Génération d'un sel aléatoire
    $salt = bin2hex(random_bytes(16)); // Longueur recommandée : au moins 16 octets (128 bits)

    // Hashage sécurisé du mot de passe
    $hashedPassword = hash('sha256', $nouvmdp . $salt);

    // Insertion des données dans la table "users" avec "activated" par défaut à 1
    $sql = "INSERT INTO users (identifiant, password, salt, activated) VALUES (?, ?, ?, ?, 1)";
    
    // Utilisation de requêtes préparées avec PDO pour éviter les injections SQL
    $stmt = $pdo->prepare($sql);

    // Protection CSRF : Vérification du jeton CSRF
    if ($_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("Erreur CSRF : Demande non autorisée.");
    }

    try {
        $stmt->execute([$identifiant, $hashedPassword, $salt, $creationDate]);
        echo "Mot de passe modifié avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de la modification du mdp : " . $e->getMessage();
    }

    // Rediriger vers la page d'accueil
    // header("Location: index.php");
    exit;
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit;
}
?>
