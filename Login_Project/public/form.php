<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once 'config.php';

// Vérifier si le formulaire a été soumis pour ajouter un nouvel utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Ajouter un nouvel utilisateur
    if ($_POST['action'] == 'add_user') {
        // Vérifier si les données nécessaires sont présentes
        if (isset($_POST['nom'], $_POST['email']) && !empty($_POST['nom']) && !empty($_POST['email'])) {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'];
            $email = $_POST['email'];

            // Requête SQL pour insérer un nouvel utilisateur avec la date d'inscription actuelle
            $sql = "INSERT INTO utilisateurs (nom, email, date_inscription) VALUES (?, ?, NOW())";

            // Préparation de la requête
            $stmt = mysqli_prepare($conn, $sql);

            // Vérifier si la préparation de la requête a réussi
            if ($stmt) {
                // Liaison des paramètres avec la requête préparée
                mysqli_stmt_bind_param($stmt, "ss", $nom, $email);

                // Exécution de la requête
                if (mysqli_stmt_execute($stmt)) {
                    // Redirection vers la page principale après l'ajout
                    header("Location: form.php");
                    exit();
                } else {
                    echo "Erreur lors de l'exécution de la requête : " . mysqli_stmt_error($stmt);
                }

                // Fermeture du statement
                mysqli_stmt_close($stmt);
            } else {
                echo "Erreur lors de la préparation de la requête : " . mysqli_error($conn);
            }
        } else {
            echo "Veuillez remplir tous les champs du formulaire.";
        }
    }

    // Supprimer tous les utilisateurs
    if ($_POST['action'] == 'delete_users') {
        // Requête SQL pour supprimer toutes les données de la table utilisateurs
        $sql_delete = "DELETE FROM utilisateurs";

        // Exécution de la requête
        if (mysqli_query($conn, $sql_delete)) {
            echo "Toutes les données ont été supprimées avec succès.";
            // Redirection vers la page principale après la suppression
            header("Location: form.php");
            exit();
        } else {
            echo "Erreur lors de la suppression des données : " . mysqli_error($conn);
        }
    }
}

// Requête SQL pour sélectionner tous les utilisateurs, triés par date d'inscription décroissante
$sql_select = "SELECT id, nom, email, date_inscription FROM utilisateurs ORDER BY date_inscription DESC";
$result = mysqli_query($conn, $sql_select);

// Vérifier si la requête a réussi
if ($result) {
    // Afficher le formulaire pour ajouter un nouvel utilisateur
    echo '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Liste des Utilisateurs</title>
        <style>
            /* Style CSS facultatif pour la mise en forme */
            body {
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
                padding: 20px;
            }
            form {
                background-color: #fff;
                padding: 20px;
                width: 400px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
            input[type="text"],
            input[type="email"],
            input[type="submit"] {
                width: calc(100% - 20px);
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 16px;
            }
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #45a049;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h2>Enregistrement des Utilisateurs</h2>
        
        <!-- Formulaire pour ajouter un nouvel utilisateur -->
        <form action="form.php" method="post">
            <input type="hidden" name="action" value="add_user">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <input type="submit" value="Ajouter Utilisateur">
        </form>

        <!-- Bouton pour supprimer toutes les données -->
        <form action="form.php" method="post">
            <input type="hidden" name="action" value="delete_users">
            <input type="submit" value="Supprimer Tous les Utilisateurs" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer tous les utilisateurs ?\');">
        </form>

        <!-- Tableau pour afficher la liste des utilisateurs -->
        <h2>Liste des Utilisateurs</h2>
        <table>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Date d\'inscription</th>
            </tr>';

    // Afficher chaque utilisateur dans le tableau
    while ($row = mysqli_fetch_assoc($result)) {
        echo '
            <tr>
                <td>' . htmlspecialchars($row['nom']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['date_inscription']) . '</td>
            </tr>';
    }

    echo '
        </table>
    </body>
    </html>';

    // Libérer le résultat
    mysqli_free_result($result);
} else {
    // Afficher un message d'erreur si la requête échoue
    echo "Erreur lors de la récupération des utilisateurs : " . mysqli_error($conn);
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
