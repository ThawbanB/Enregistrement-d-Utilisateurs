<?php
// Informations d'identification de la base de données
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'login_project');

// Connexion à la base de données MySQL
$conn = mysqli_connect('localhost', 'root', '', 'login_project');

// Vérifier la connexion
if($conn === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
?>
