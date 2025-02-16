<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travailleurs";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse e-mail invalide !";
    } else {
        if ($role == "admin") {
            $sql = "SELECT * FROM administration WHERE email=?";
            $redirect_page = "iindex.php"; // توجيه المشرفين إلى الصفحة الرئيسية
        } else {
            $sql = "SELECT ta.password_hash, t.nom, t.prenom FROM travailleurs_auth ta 
                    JOIN travailleurs t ON ta.email = t.email WHERE ta.email=?";
            $redirect_page = "worker_list.php";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // التحقق من كلمة المرور
            if (password_verify($password, $row['password_hash']) || $password === $row['password_hash']) {
                $_SESSION['logged_in'] = true;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;
                if ($role == "worker") {
                    $_SESSION['nom'] = $row['nom'];
                    $_SESSION['prenom'] = $row['prenom'];
                }
                header("Location: $redirect_page");
                exit;
            } else {
                $error = "Identifiants incorrects !";
            }
        } else {
            $error = "Identifiants incorrects !";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
        }
        .login-container {
            background: linear-gradient(135deg, #2a2a3a, #1e1e2f);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            width: 350px;
            box-shadow: 0 4px 15px rgba(0, 255, 255, 0.5);
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        input, select {
            background: #2a2a3a;
            color: #fff;
        }
        button {
            background: #00e5ff;
            color: #000;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #00bcd4;
        }
        /* تمت إزالة روابط إنشاء الحساب ونسيان كلمة المرور */
    </style>
    <script>
        function toggleRegisterButton() {
            var role = document.getElementById("role").value;
            // لم يعد هنالك حاجة لإظهار زر التسجيل
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <select name="role" id="role" onchange="toggleRegisterButton()">
                <option value="admin">Administrateur</option>
                <option value="worker">Travailleur</option>
            </select>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
