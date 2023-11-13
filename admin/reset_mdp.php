<?php
session_start();

require_once('../process_php/europe.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['connected'])) {
    // Récupérer l'ID de l'utilisateur à réinitialiser depuis le formulaire
    $user_id = $_POST["user_id"];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    if (isset($_POST["nouvmdp"])) {
        // Récupérer le nouveau mot de passe
        $nouvmdp = $_POST["nouvmdp"];

        // Génération d'un sel aléatoire
        $salt = bin2hex(random_bytes(16)); // Longueur recommandée : au moins 16 octets (128 bits)

        // Hashage sécurisé du mot de passe
        $hashedPassword = hash('sha256', $nouvmdp . $salt);

        // Mise à jour du mot de passe dans la table "users"
        $sql = "UPDATE users SET password=?, salt=? WHERE id=?";
        
        // Utilisation de requêtes préparées avec PDO pour éviter les injections SQL
        $stmt = $pdo->prepare($sql);

        // Protection CSRF : Vérification du jeton CSRF
        if ($_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die("Erreur CSRF : Demande non autorisée.");
        }

        try {
            $stmt->execute([$hashedPassword, $salt, $user_id]);
            echo "Mot de passe modifié avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la modification du mot de passe : " . $e->getMessage();
        }
    }

    // Afficher le formulaire pour entrer le nouveau mot de passe
    echo "<form action='reset_mdp.php' method='post'>";
    echo "<input type='hidden' name='user_id' value='$user_id'>";
    echo "<label for='nouvmdp'>Nouveau mot de passe :</label>";
    echo "<input type='password' name='nouvmdp' required><br>";
    echo "<input type='submit' value='Valider'>";
    echo "</form>";

    // Bouton de retour vers la page d'accueil
    echo "<form action='index.php' method='post'>";
    echo "<input type='submit' value='Retour'>";
    echo "</form>";
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit;
}
?>
