<?php

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

    // Obtenez l'extension du fichier
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedfileExtensions)) {
        $newFileName = generateNewFilename($fileName);
        $uploadFileDir = '../img_import/'; // PENSER dossier accessible en écriture
        $dest_path = $uploadFileDir . $newFileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            echo 'File is successfully uploaded.';
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
