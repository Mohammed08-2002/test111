<?php
// Contrôleur : Initialisation de la session et connexion à la base de données
session_start();

$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$baseDeDonnees = "travailleurs";

// Connexion à la base de données MySQL
$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $dateNaissance = $_POST["date_naissance"];
    $adresse = $_POST["adresse"];
    $dateCreation = date("Y-m-d H:i:s"); // Génération automatique de la date de création

    // Vérification des champs obligatoires
    if (!empty($nom) && !empty($prenom) && !empty($email)) {
        // Requête SQL d'insertion
        $requete = "INSERT INTO travailleurs (nom, prenom, email, telephone, date_naissance, adresse, created_at) 
                    VALUES ('$nom', '$prenom', '$email', '$telephone', '$dateNaissance', '$adresse', '$dateCreation')";
        if ($connexion->query($requete) === TRUE) {
            $message = "✅ L'employé a été ajouté avec succès !";
        } else {
            $message = "❌ Erreur lors de l'ajout des données : " . $connexion->error;
        }
    } else {
        $message = "⚠️ Veuillez remplir les champs obligatoires !";
    }
}

$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un nouvel employé</title>
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
        h1 {
            margin-bottom: 30px;
            color: #00d4ff;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"] {
            width: 100%;
            border: none;
            outline: none;
            inputmode: latin;
        }
        input[type="submit"] {
            background-color: #00d4ff;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #00a3cc;
        }
        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px;
            background-color: #00d4ff;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #00a3cc;
        }
        .message {
            margin-top: 20px;
            font-size: 14px;
        }
        .error {
            color: red;
        }
        .back-btn {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background-color: #ff4b5c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background-color: #cc3b4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouvel employé</h1>
        <form action="" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="email">E-mail :</label>
            <input type="email" id="email" name="email" required>

            <label for="telephone">Téléphone :</label>
            <input type="tel" id="telephone" name="telephone">

            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance">

            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse">
             
            <input type="submit" value="➕ Ajouter">
        </form>
        <a href="results.php" class="btn">📋 Afficher les résultats</a>
        <a href="iindex.php" class="back-btn">⬅ Retour</a>
        <?php if (!empty($message)) { ?>
            <p class="message <?php echo strpos($message, '⚠️') !== false ? 'error' : ''; ?>">
                <?php echo $message; ?>
            </p>
        <?php } ?>
    </div>
</body>
</html>
