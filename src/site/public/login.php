<?php
session_start();
require '../appel/pdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($email) || empty($password)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        //récupère l'utilisateur depuis la bd
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])){
            //si identification réussie, démarrage de la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../privee/dashboard.php");
            exit();
        } else {
            $error = "identifiants incorrects.";
        }
    }
}
?>

<!-- Formulaire de connexion -->
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Connexion</title>
        <link rel="stylesheet" href="../assets/css/relog.css">
        <link rel="stylesheet" href="../assets/css/header.css">
    </head>
    <body>
        <?php include '../header/header.php'; ?>
        <div class="container">    
            <div class="container1">
            <form method="POST" action="">
                <h2>Connexion</h2>
                <p>Nouveau sur notre site ? <br><a href="register.php"> S'inscrire</a></p>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
                <input type="submit" value="Se connecter"/>
                <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>";?>
            </form>
            </div>
            <div class="drop drop-1"></div>
            <div class="drop drop-2"></div>
            <div class="drop drop-3"></div>
            <div class="drop drop-4"></div>
            <div class="drop drop-5"></div>
        </div>
    </body>
</html>