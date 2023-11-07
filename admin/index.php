<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connected'])) {
    // Rediriger vers la page de connexion
    header('Location: connexion.php');
    exit();
}

// Déconnecter automatiquement après 5 minutes d'inactivité
// $SESSION['$timeout'] = 300; // 5 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['$timeout'])) {
    // Détruire toutes les variables de session et déconnecter l'utilisateur
    session_unset();
    session_destroy();

    // Rediriger vers la page de connexion
    header('Location: connexion.php');
    exit();
}
header("Content-Type: text/html;charset=UTF-8");
// Mettre à jour le timestamp de dernière activité
$_SESSION['last_activity'] = time();

// Load data from BDD
require_once('../process_php/europe.php');

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer la liste des utilisateurs depuis la base de données
$sql = "SELECT id, identifiant, activated FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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

    <form action="deconnexion.php" method="post">
        <input type="submit" value="Déconnexion" id="deco_btn">
    </form>

    <h2>Gérer les utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Identifiant</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['identifiant'] ?></td>
                    <td><?= $user['activated'] == 1 ? 'Activé' : 'Désactivé' ?></td>
                    <td>
                        <?php if ($user['activated'] == 1) : ?>
                            <form action="desactiver_utilisateur.php" method="post">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="submit" value="Désactiver">
                            </form>
                        <?php else : ?>
                            <form action="reactiver_utilisateur.php" method="post">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="submit" value="Réactiver">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
