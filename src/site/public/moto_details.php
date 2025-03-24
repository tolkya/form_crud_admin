<?php
session_start();
require '../appel/pdo.php';

// Vérification et validation de l'ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: moto.php"); // Redirige vers la liste des motos en cas d'ID invalide
    exit();
}

$moto_id = $_GET['id'];

// Récupérer les infos de la moto avec le propriétaire
$stmt = $pdo->prepare("
    SELECT motos.*, users.username 
    FROM motos 
    JOIN users ON motos.user_id = users.id 
    WHERE motos.id = ?
");
$stmt->execute([$moto_id]);
$moto = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la moto n'existe pas, rediriger vers la page d'accueil des motos
if (!$moto) {
    header("Location: moto.php");
    exit();
}

// Vérifier si l'image existe
$image_path = "../uploads/" . htmlspecialchars($moto['Image']);
if (!file_exists($image_path) || empty($moto['Image'])) {
    $image_path = "../assets/img/default-moto.jpg"; // Image par défaut
}
?>

<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/moto_details.css">
        <link rel="stylesheet" href="../assets/css/header.css">
        <title><?php echo htmlspecialchars_decode($moto['Modele']); ?> - Détails</title>
    </head>
    <body>
        <?php include '../header/header.php'; ?>

        <div class="moto-container">
            <h1><?php echo htmlspecialchars($moto['Modele']); ?> (<?php echo htmlspecialchars_decode($moto['Annee']); ?>)</h1>
            <span class="proprietaire">Propriétaire : <?php echo htmlspecialchars_decode($moto['username']); ?></span>

            <div class="moto-details">
                <a href="<?php echo $image_path; ?>" target="_blank">
                    <img src="<?php echo $image_path; ?>" alt="Image de la moto">
                </a>

                <div class="info">
                    <p><strong>Édition :</strong> <?php echo htmlspecialchars_decode($moto['Edition']); ?></p>
                    <p><strong>Couleur :</strong> <?php echo htmlspecialchars_decode($moto['Couleur']); ?></p>
                    <p><strong>Catégorie :</strong> <?php echo htmlspecialchars_decode($moto['Categorie']); ?></p>
                    <p><strong>Cylindrée :</strong> <?php echo htmlspecialchars_decode($moto['Cylindree']); ?> cm³</p>
                    <p><strong>Puissance :</strong> <?php echo htmlspecialchars_decode($moto['Chevaux']); ?> ch</p>
                    <p><strong>Utilité :</strong> <?php echo htmlspecialchars_decode($moto['Utilite']); ?></p>
                    <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars_decode($moto['Description'])); ?></p>
                </div>
            </div>

            <a href="moto.php" class="btn-retour">⬅ Retour aux motos</a>
        </div>
    </body>
</html>
