<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

// التحقق مما إذا كان العامل مسجل الدخول
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'worker') {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email']; // استرجاع البريد الإلكتروني من الجلسة

// الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// جلب بيانات العامل الذي قام بتسجيل الدخول فقط
$sql = "SELECT * FROM travailleurs WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// التحقق مما إذا كانت البيانات موجودة
if ($result->num_rows === 0) {
    echo "<p style='color: red;'>Aucune donnée trouvée pour cet utilisateur.</p>";
    exit;
}

$row = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil du Travailleur</title>
    <style>
        body {
            background-color: #1e1e2f;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        h2 {
            color: #00e5ff;
        }
        table {
            width: 60%;
            border-collapse: collapse;
            background: #2a2a3a;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 255, 255, 0.5);
        }
        th, td {
            padding: 12px;
            border: 1px solid #00e5ff;
            text-align: left;
        }
        th {
            background-color: #00e5ff;
            color: #000;
        }
        tr:hover {
            background-color: #3a3a4f;
        }
        .back-btn {
            margin-top: 20px;
            background: #ff4b5c;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 200px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            background: #e84150;
        }
    </style>
</head>
<body>

    <h2>Profil du Travailleur</h2>

    <table>
        
        <tr><th>Nom</th><td><?= htmlspecialchars($row['nom']) ?></td></tr>
        <tr><th>Prénom</th><td><?= htmlspecialchars($row['prenom']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($row['email']) ?></td></tr>
        <tr><th>Téléphone</th><td><?= htmlspecialchars($row['telephone'] ?? 'N/A') ?></td></tr>
        <tr><th>Date de Naissance</th><td><?= htmlspecialchars($row['date_naissance'] ?? 'N/A') ?></td></tr>
        <tr><th>Adresse</th><td><?= htmlspecialchars($row['adresse'] ?? 'N/A') ?></td></tr>
        <tr><th>Créé le</th><td><?= htmlspecialchars($row['created_at']) ?></td></tr>
    </table>

    <a href="login.php" class="back-btn">Retour</a>

</body>
</html>
