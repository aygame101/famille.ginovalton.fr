<?php

require_once('europe.php');

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction pour ajouter un suffixe unique de 8 caractères aléatoires au nom de fichier original
function generateNewFilename($originalFileName) {
    // Séparer le nom de fichier de son extension
    $fileNameCmps = explode(".", $originalFileName);
    $fileExtension = strtolower(array_pop($fileNameCmps));
    $baseName = implode(".", $fileNameCmps); // Si le nom de fichier a plus d'un point

    // Générer un suffixe aléatoire
    $randomSuffix = substr(md5(uniqid(rand(), true)), 0, 8);

    // Concaténer le nom de base, le suffixe et l'extension pour obtenir le nouveau nom de fichier
    $newFileName = $baseName . '_' . $randomSuffix . '.' . $fileExtension;
    return $newFileName;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $allowedfileExtensions = array('png', 'jpeg', 'jpg', 'heic');

    // Obtenez l'extension du fichier // A partir d'ici ça fonctionne pas
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedfileExtensions)) {
        $newFileName = generateNewFilename($fileName);
        $uploadFileDir = '../img_import/'; // PENSER dossier accessible en écriture
        $dest_path = $uploadFileDir . $newFileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $message = $_POST['message'] ?? '';
            $user_id = $_SESSION['user_id'] ?? '0'; // Remplacez '0' par une gestion d'erreur appropriée

            // Insertion des informations dans la base de données
            $sql = "INSERT INTO photos (chemin, message, user) VALUES (:chemin, :message, :user)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':chemin' => $dest_path,
                ':message' => $message,
                ':user' => $user_id
            ]);
               
            echo 'File is successfully uploaded and data has been saved to the database.';
        } else {
            echo 'There was an error moving the file to upload directory. Please make sure the upload directory is writable.';
        }
    } else {
        echo 'Upload failed. Allowed file types: ' . implode(', ', $allowedfileExtensions);
    }
} else {
    echo 'No file uploaded.';
}
?>