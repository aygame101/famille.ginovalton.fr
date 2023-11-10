-- Requete pour creer la table "user"
CREATE TABLE fm_gv_fr.users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR(255) NOT NULL,
    date_creation DATETIME NOT NULL,
    activated TINYINT(1) NOT NULL
);


CREATE TABLE fm_gv_fr.photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chemin VARCHAR(255),
    message TEXT,
    user INT
);
