<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupérer les données du travailleur à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM travailleurs WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();
    } else {
        echo "❌ Travailleur introuvable !";
        exit;
    }
}

// Mettre à jour les données du travailleur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $date_naissance = $_POST["date_naissance"];
    $adresse = $_POST["adresse"];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($telephone)) {
        $update_sql = "UPDATE travailleurs SET nom='$nom', prenom='$prenom', email='$email', telephone='$telephone', date_naissance='$date_naissance', adresse='$adresse' WHERE id=$id";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: results.php");
            exit;
        } else {
            echo "⚠️ Erreur lors de la mise à jour : " . $conn->error;
        }
    } else {
        echo "⚠️ Veuillez remplir tous les champs !";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les informations du travailleur</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to bottom, #0f2027, #203a43, #2c5364);
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .container {
            text-align: center;
            background: #1c1c1c;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            width: 400px;
            position: relative;
        }
        .container h1 {
            margin-bottom: 30px;
            color: #00d4ff;
        }
        .container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            outline: none;
        }
        .container input[type="submit"] {
            background-color: #00d4ff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        .container input[type="submit"]:hover {
            background-color: #00a3cc;
        }
        .container a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #00d4ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier les informations du travailleur</h1>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $worker['id']; ?>">
            <input type="text" name="nom" value="<?php echo $worker['nom']; ?>" required placeholder="Nom">
            <input type="text" name="prenom" value="<?php echo $worker['prenom']; ?>" required placeholder="Prénom">
            <input type="email" name="email" value="<?php echo $worker['email']; ?>" required placeholder="Email">
            <input type="text" name="telephone" value="<?php echo $worker['telephone']; ?>" required placeholder="Téléphone">
            <input type="date" name="date_naissance" value="<?php echo $worker['date_naissance']; ?>" required>
            <input type="text" name="adresse" value="<?php echo $worker['adresse']; ?>" required placeholder="Adresse">
            <input type="submit" value="💾 Mettre à jour">
        </form>
        <a href="results.php">🔙 Annuler</a>
    </div>
</body>
</html>
