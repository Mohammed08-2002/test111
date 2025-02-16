<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM travailleurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $worker = $result->fetch_assoc();
} else {
    header("Location: liste_travailleurs.php?error=not_found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du travailleur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d1b2a;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 50%;
            padding: 20px;
            background: linear-gradient(135deg, #1b263b, #0d1b2a);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 255, 255, 0.5);
            text-align: center;
        }
        h2 {
            color: cyan;
        }
        p {
            font-size: 18px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 8px;
        }
        .btn {
            margin-top: 15px;
            display: inline-block;
            padding: 10px 20px;
            color: #000;
            background: cyan;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn:hover {
            background: #00bcd4;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Détails du travailleur</h2>
    <p><strong>Nom :</strong> <?php echo htmlspecialchars($worker["nom"]); ?></p>
    <p><strong>Prénom :</strong> <?php echo htmlspecialchars($worker["prenom"]); ?></p>
    <p><strong>Email :</strong> <?php echo htmlspecialchars($worker["email"]); ?></p>
    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($worker["telephone"]); ?></p>
    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($worker["adresse"]); ?></p>
    
    <a href="results.php" class="btn">Retour à la liste</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
