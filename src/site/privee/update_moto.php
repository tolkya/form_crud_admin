<?php
session_start();
require '../appel/pdo.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: moto.php");
    exit();
}

// Vérifier si l'ID de la moto est bien envoyé
if (!isset($_POST['moto_id'])) {
    echo "Erreur : ID de moto manquant.";
    exit();
}

$moto_id = $_POST['moto_id'];
$user_id = $_SESSION['user_id'];

// Récupération des nouvelles données
$modele = $_POST['Modele'];
$description = $_POST['Description'];
$utilite = $_POST['Utilite'];
$categorie = $_POST['Categorie'];
$edition = $_POST['Edition'];
$annee = !empty($_POST['Annee']) && is_numeric($_POST['Annee']) ? (int)$_POST['Annee'] : NULL;
$couleur = $_POST['Couleur'];
$cylindree = !empty($_POST['Cylindree']) ? (int)$_POST['Cylindree'] : NULL;
$chevaux = !empty($_POST['Chevaux']) ? (float)$_POST['Chevaux'] : NULL;

// Gérer l'upload de la nouvelle image
if (!empty($_FILES['Image']['name'])) {
    $image_name = basename($_FILES['Image']['name']);
    $target_path = "../uploads/" . $image_name;

    // Vérifier et déplacer l'image
    if (move_uploaded_file($_FILES['Image']['tmp_name'], $target_path)) {
        // Mettre à jour avec la nouvelle image
        $stmt = $pdo->prepare("UPDATE motos 
            SET Modele=?, Description=?, Utilite=?, Categorie=?, Edition=?, Annee=?, Couleur=?, Cylindree=?, Chevaux=?, Image=? 
            WHERE id=? AND user_id=?");
        $stmt->execute([$modele, $description, $utilite, $categorie, $edition, $annee, $couleur, $cylindree, $chevaux, $image_name, $moto_id, $user_id]);
    } else {
        echo "Erreur lors du téléchargement de l'image.";
        exit();
    }
} else {
    // Mettre à jour sans changer l'image
    $stmt = $pdo->prepare("UPDATE motos 
        SET Modele=?, Description=?, Utilite=?, Categorie=?, Edition=?, Annee=?, Couleur=?, Cylindree=?, Chevaux=? 
        WHERE id=? AND user_id=?");
    $stmt->execute([$modele, $description, $utilite, $categorie, $edition, $annee, $couleur, $cylindree, $chevaux, $moto_id, $user_id]);
}

// Rediriger vers le dashboard
header("Location: dashboard.php");
exit();
