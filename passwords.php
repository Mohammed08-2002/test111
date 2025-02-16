<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (!empty($email) && !empty($password)) {
        // Vérifier si l'email existe dans la table travailleurs
        $stmt = $conn->prepare("SELECT id FROM travailleurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Vérifier si l'email est déjà enregistré dans travailleurs_auth
            $stmt = $conn->prepare("SELECT id FROM travailleurs_auth WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $auth_result = $stmt->get_result();
            
            if ($auth_result->num_rows == 0) {
                // Insérer l'email et le mot de passe haché dans travailleurs_auth
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO travailleurs_auth (email, password_hash) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $hashed_password);
                
                if ($stmt->execute()) {
                    $message = "Mot de passe créé avec succès !";
                } else {
                    $message = "Une erreur s'est produite lors de la création du compte.";
                }
            } else {
                $message = "L'email est déjà enregistré.";
            }
        } else {
            $message = "L'email n'existe pas dans la base de données.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d1b2a;
            color: white;
            text-align: center;
        }
        .container {
            margin: 50px auto;
            width: 50%;
            background-color: #1b263b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        input, button {
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            border: 1px solid cyan;
            background-color: #0a192f;
            color: white;
        }
        button {
            background-color: #005f73;
            cursor: pointer;
        }
        button:hover {
            background-color: #0a192f;
        }
        .message {
            margin-top: 15px;
            color: yellow;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Créer un nouveau mot de passe</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Entrez votre email" required><br>
            <input type="password" name="password" placeholder="Entrez un mot de passe" required><br>
            <button type="submit">Créer</button>
        </form>
        <div class="message"> <?php echo $message; ?> </div>
        <br>
        <a href="iindex.php" style="color: cyan; text-decoration: none;">↩ Retour à la page principale</a>
    </div>
</body>
</html>
