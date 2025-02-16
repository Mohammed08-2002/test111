<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupérer le nom d'utilisateur depuis la table administration
$sql = "SELECT username FROM administration LIMIT 1";
$result = $conn->query($sql);

$username = "Utilisateur inconnu"; // Valeur par défaut si aucun utilisateur trouvé

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'administration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d1b2a;
            color: white;
            text-align: center;
        }
        .container {
            margin: 50px auto;
            width: 90%;
            background-color: #1b263b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        h2 {
            color: cyan;
        }
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .nav-buttons a {
            text-decoration: none;
            background-color: #005f73;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .nav-buttons a:hover {
            background-color: #0a192f;
        }
        .username {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="username">Bonjour, <?php echo htmlspecialchars($username); ?> </div>
    <h2>Panneau d'administration</h2>
    <div class="nav-buttons">
        <a href="index.php">Ajouter un travailleur</a>
        <a href="results.php">Liste des travailleurs</a>
        <a href="passwords.php">Liste des mots de passe</a>
    </div>
</div>

</body>
</html>
