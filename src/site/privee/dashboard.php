<?php

// Démarre la session pour gérer l'authentification de l'utilisateur
session_start();

// Inclusion du fichier contenant la connexion à la base de données
require_once '../appel/pdo.php';

// Vérifie si l'utilisateur est connecté, sinon il est redirigé vers la page d'accueil
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/moto.php"); // Redirection vers la page d'accueil
    exit(); // Arrêt du script
}

// Récupère l'ID de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Prépare une requête SQL pour récupérer toutes les motos de cet utilisateur
$stmt = $pdo->prepare("SELECT * FROM motos WHERE user_id = ?");
$stmt->execute([$user_id]); // Exécute la requête avec l'ID de l'utilisateur
$motos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les motos sous forme de tableau associatif

?>
<html>
    <head>
        <title>TON GARAGE</title> <!-- Titre de la page -->
        <link rel="stylesheet" href="../assets/css/header.css">
        <link rel="stylesheet" href="../assets/css/dashboard.css">
    </head>
    <body>
        <?php include '../header/header.php'; ?>
        <h2 class="entry">Ton garage personnel <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <!-- Liens pour ajouter une moto -->
        <a href="add_moto.php" class="a">Ajouter une moto</a>

        <h3>Vos Motos</h3>

        <!-- Vérifie s'il y a des motos à afficher -->
        <?php if ($motos): ?>
            <div class=ensemble>
                <?php foreach ($motos as $moto): ?> <!-- Boucle sur chaque moto de l'utilisateur -->
                    <div class=pourchaque>
                        <!-- Affichage de l'image de la moto -->
                        <img src="../uploads/<?php echo htmlspecialchars($moto['Image']); ?>" width="350" alt="Moto">
                        <!-- Affichage des détails de la moto -->
                            <h2><strong><?php echo htmlspecialchars_decode($moto['Modele'] ?? ''); ?></strong></h2>
                            <p><?php echo htmlspecialchars_decode($moto['Description'] ?? ''); ?></p>
                            <p><?php echo htmlspecialchars_decode($moto['Edition'] ?? ''); ?></p>
                            <p><?php echo htmlspecialchars_decode($moto['Utilite'] ?? ''); ?></p>
                            <p><?php echo htmlspecialchars_decode($moto['Couleur'] ?? ''); ?></p>
                            <p><?php echo htmlspecialchars_decode($moto['Annee'] ?? ''); ?></p>
                            <p><?php echo htmlspecialchars_decode($moto['Categorie'] ?? ''); ?></p>
                            <p><?php echo htmlspecialchars_decode($moto['Cylindree'] ?? '0'); ?>CC</p>
                            <p><?php echo htmlspecialchars_decode($moto['Chevaux'] ?? '0'); ?>Ch</p>

                        <form action="edit_moto.php" method="POST" style="display:inline;">
                            <input type="hidden" name="moto_id" value="<?php echo $moto['id']; ?>">
                            <button type="submit">Modifier</button>
                        </form>


                        <!-- Formulaire sécurisé pour supprimer une moto via POST -->
                        <form method="POST" action="delete_moto.php" style="display:inline;">
                            <!-- Champ caché contenant l'ID de la moto -->
                            <input type="hidden" name="id" value="<?php echo $moto['id']; ?>">
                            <!-- Bouton de suppression avec confirmation -->
                            <input type="submit" onclick="return confirm('Êtes-vous sûr ?');" value="supprimer">
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Vous n'avez pas encore ajouté de moto.</p>
        <?php endif; ?>
    </body>
</html>
