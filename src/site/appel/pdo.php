<?php    
// config/config.php
$host = 'db';
$dbname = 'moto4';
$username = 'root'; // Modifie si nécessaire
$password = 'root'; // Modifie si nécessaire

try {
    // Connexion à la base de données avec UTF-8 forcé
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les erreurs en mode Exception
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8mb4" // Force l'encodage UTF-8
    ]);
} catch (PDOException $e) {
    // Log l'erreur au lieu de l'afficher (plus sécurisé en prod)
    error_log("Erreur de connexion à la base de données : " . $e->getMessage());
    die("Une erreur s'est produite. Veuillez réessayer plus tard.");
}
?>
