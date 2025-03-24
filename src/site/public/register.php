<?php
    session_start();
    require '../appel/pdo.php';

// Vérifie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupère et sécurise les données du formulaire
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Vérifie si les champs sont remplis
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifie si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);

        if ($stmt->fetch()) {
            $error = "Cet utilisateur existe déjà.";
        } else {
            // Hache le mot de passe
            $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

            // Insère le nouvel utilisateur dans la base
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: ../privee/dashboard.php");
                exit();
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!-- Formulaire d'inscription -->
<html lang="fr">
    <head>
        <title>Inscription</title>
        <link rel="stylesheet" href="../assets/css/relog.css">
        <link rel="stylesheet" href="../assets/css/header.css">
    </head>
    <body>
        <?php include '../header/header.php'; ?>
        <div class="container">    
            <div class="container1">
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="">
                <h2>Inscription</h2>
                <p>Déjà inscrit ? <br> <a href="./login.php">Connectez-vous</a></p>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="confirm_password" placeholder="Confirmation mot de passe" required><br>
                <input type="submit" value="S'inscrire"/>
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