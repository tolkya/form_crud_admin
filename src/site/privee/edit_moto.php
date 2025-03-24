<?php
// Démarrer la session
session_start();
require '../appel/pdo.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: moto.php");
    exit();
}

// Vérifier si un ID de moto a été envoyé via POST
if (!isset($_POST['moto_id'])) {
    echo "Erreur : Aucun ID de moto fourni.";
    exit();
}

$moto_id = $_POST['moto_id'];
$user_id = $_SESSION['user_id'];

// Récupérer les infos actuelles de la moto
$stmt = $pdo->prepare("SELECT * FROM motos WHERE id = ? AND user_id = ?");
$stmt->execute([$moto_id, $user_id]);
$moto = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si la moto existe bien
if (!$moto) {
    echo "Erreur : Moto introuvable.";
    exit();
}
?>

<html>
    <head>
        <title>Modifier Moto</title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="../assets/css/addedit.css">
        <link rel="stylesheet" href="../assets/css/header.css">

    </head>
    <body>
        <?php include '../header/header.php'; ?>
        <h2>Modifier votre moto</h2>
        <form action="update_moto.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="moto_id" value="<?php echo htmlspecialchars($moto['id']); ?>">

            <label>Modèle :</label>
            <input type="text" name="Modele" value="<?php echo htmlspecialchars($moto['Modele']); ?>" required><br>

            <label>Édition :</label>
            <input type="text" name="Edition" value="<?php echo htmlspecialchars($moto['Edition']); ?>"><br>

            <label>Catégorie :</label>
            <select id="Categorie" name="Categorie" >
            <?php
                $categories = ["Adventure", "Cross", "Cruiser", "Enduro", "Roadster", "Routiere", "Sportive", "Supermotard", "Trail"];
                foreach ($categories as $cat) {
                    $selected = ($moto['Categorie'] === $cat) ? 'selected' : '';
                    echo "<option value=\"$cat\" $selected>$cat</option>";
                }
                ?>
                <option value="" <?php echo ($moto['Categorie'] === '') ? 'selected' : ''; ?>>-- autre --</option>
            </select>

            <label>Cylindrée :</label>
            <input type="number" name="Cylindree" value="<?php echo htmlspecialchars($moto['Cylindree']); ?>" min="50" max="3000" step="1"> CC

            <label>Cheavaux :</label>
            <input type="number" name="Chevaux" value="<?php echo htmlspecialchars($moto['Chevaux']); ?>"  min="10" max="450" step="0.1"> Ch

            <label>Année :</label>
            <input type="number" name="Annee" value="<?php echo htmlspecialchars($moto['Annee']); ?>" min="1900" max="<?php echo date('Y'); ?>"><br>

            <label>Couleur :</label>
            <input type="text" name="Couleur" value="<?php echo htmlspecialchars($moto['Couleur']); ?>"><br>

            <label>Image actuelle :</label><br>
            <img src="../uploads/<?php echo htmlspecialchars($moto['Image']); ?>" width="150" alt="Moto"><br>

            <label>Nouvelle image :</label>
            <input type="file" name="Image"><br>

            <label>Description :</label>
            <textarea name="Description"><?php echo htmlspecialchars($moto['Description']); ?></textarea><br><br>

            <label>Usage :</label>
            <input type="text" name="Utilite" value="<?php echo htmlspecialchars($moto['Utilite'] ?? ''); ?>"><br>

            <input type="submit" value="mettre à jour">
           
        </form>
    </body>
</html>
