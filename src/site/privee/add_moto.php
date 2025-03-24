<?php
// public/add_moto.php

session_start();
require_once '../appel/pdo.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = htmlspecialchars(trim($_POST['description']));
    $utilite = htmlspecialchars(trim($_POST['utilite']));
    $modele = htmlspecialchars(trim($_POST['modele']));
    $edition = htmlspecialchars(trim($_POST['edition']));
    $couleur = htmlspecialchars(trim($_POST['couleur']));
    $annee = htmlspecialchars(trim($_POST['annee']));
    $categorie = htmlspecialchars(trim($_POST['categorie']));
    $cylindree = htmlspecialchars(trim($_POST['cylindree']));
    $chevaux = htmlspecialchars(trim($_POST['chevaux']));
    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;

        // Déplace l'image vers le dossier uploads
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insère la moto dans la base de données
            $stmt = $pdo->prepare("INSERT INTO motos (user_id, image, description, utilite, modele, edition, couleur, annee, categorie, cylindree, chevaux) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $image_name, $description, $utilite, $modele, $edition, $couleur, $annee, $categorie, $cylindree, $chevaux]);
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Erreur lors du téléchargement de l'image.";
        }
    } else {
        $error = "Veuillez sélectionner une image valide.";
    }
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Ajouter une moto</title>
        <link rel="stylesheet" href="../assets/css/addedit.css">
        <link rel="stylesheet" href="../assets/css/header.css">
    </head>
    <body>
        <?php include '../header/header.php'; ?>
        <h2>Ajouter une Moto</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <p>Ton modèle :</p>
            <input type="text" name="modele" placeholder="CBR 1000 RR-R" required><br>
            <p>Une édition particulère ?</p>
            <input type="text" name="edition" placeholder="Fireblade 100th TT Start Limited Edition"><br>
            <p>Quelle année ?</p>
            <input type="number" name="annee" placeholder="YYYY" min="1948" max="2025" required>
            <p>Les plus belles prise :</p>
            <input type="file" name="image" required><br>
            <p>Quelles couleurs ?</p>
            <input type="text" name="couleur" placeholder="Black" required><br>
            <p>Quelle est la catégorie de ta moto ?</p>
            <select id="categorie" name="categorie">
                <option value="">-- autre --</option>
                <option value="Adventure">Adventure</option>
                <option value="Cross">Cross</option>
                <option value="Cruiser">Cruiser</option>
                <option value="Enduro">Enduro</option>
                <option value="Roadster">Roadster</option>
                <option value="Routiere">Routiere</option>
                <option value="Sportive">Sportive</option>
                <option value="Supermotard">Supermotard</option> 
                <option value="Trail">Trail</option>    
            </select>
            <p>Quelle cylindrée ?</p>
            <input type="number" name="cylindree" placeholder="650cc" min="50" max="3000" step="1"> CC
            <p>Combien de Chevaux ?</p>
            <input type="number" name="chevaux" placeholder="95ch" min="5" max="450"> Ch
            <p>Des point à mettre en avant ?</p>
            <textarea name="description" placeholder="Moteur très souple" required></textarea><br>
            <p>Pour quelles occasions t'en sers-tu ?</p>
            <textarea name="utilite" placeholder="Balade" required></textarea><br>

            <button type="submit">Ajouter</button>
            <?php
                if (isset($_POST['retour'])) {
                    header('Location: dashboard.php'); // Remplace par l'URL de ton choix
                    exit();
                }
                ?>

            <form method="post">
                    <button type="submit" name="retour">Retour</button>
            </form> <!--bouton pour faire retour-->
        </form>
    </body>
</html>