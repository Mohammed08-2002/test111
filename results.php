<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM travailleurs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $response = ['success' => false];

    if ($stmt->execute()) {
        $response['success'] = true;
    }

    $stmt->close();
    echo json_encode($response);
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT * FROM travailleurs";

if (!empty($search)) {
    $sql .= " WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR telephone LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des travailleurs</title>
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
        }
        h2 {
            color: cyan;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 50%;
            border-radius: 5px;
            border: 1px solid cyan;
            background-color: #0a192f;
            color: white;
        }
        button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background-color: #005f73;
            color: white;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid cyan;
            text-align: center;
        }
        th {
            background-color: #005f73;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #0a192f;
        }
        tr.selected {
            background-color: #ff9800 !important;
        }
        .return-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .return-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Liste des travailleurs</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">🔍 Rechercher</button>
    </form>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Date de naissance</th>
            <th>Adresse</th>
            <th>Date de création</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["nom"]); ?></td>
                <td><?php echo htmlspecialchars($row["prenom"]); ?></td>
                <td><?php echo htmlspecialchars($row["email"]); ?></td>
                <td><?php echo htmlspecialchars($row["telephone"]); ?></td>
                <td><?php echo htmlspecialchars($row["date_naissance"]); ?></td>
                <td><?php echo htmlspecialchars($row["adresse"]); ?></td>
                <td><?php echo date("Y-m-d H:i", strtotime($row["created_at"])); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<a href="iindex.php" class="return-button">&#8592; Retour</a>

</body>
</html>
