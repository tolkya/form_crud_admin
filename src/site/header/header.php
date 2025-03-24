<?php
require_once '../appel/pdo.php'; // On inclut le fichier pour se connecter à la base de données via PDO

// Vérifier si l'utilisateur est connecté en cherchant l'id utilisateur dans la session
$user_id = $_SESSION['user_id'] ?? null; // Si 'user_id' existe dans la session, on le récupère, sinon null
$username = null; // Initialisation de la variable username à null

if ($user_id) { // Si l'utilisateur est connecté (id présent)
    // Préparation d'une requête SQL pour récupérer le nom d'utilisateur correspondant à l'id
    $stmtUser = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]); // Exécution de la requête avec l'id utilisateur
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC); // Récupération du résultat sous forme de tableau associatif
    $username = $user ? htmlspecialchars($user['username']) : null; // Si un utilisateur est trouvé, on sécurise et stocke son username
}
?>

<header>
    <a href="../public/moto.php" class="logo"><img src="../assets/images/logo.png" alt="Logo du site" class="logo-img"></a>
    <h2 id="title">Honda de France</h2>
    <div class="auth">
        <?php if ($username): ?>
            <a type=button class="username" href="../privee/dashboard.php">👤 <?php echo $username; ?></a>
            <a href="../privee/logout.php" class="btn">Se déconnecter</a>
        <?php else: ?>
            <a href="login.php" class="btn">Se connecter</a>
            <a href="register.php" class="btn">S'inscrire</a>
        <?php endif; ?>
    </div>
</header>
