<?php
// Démarre la session pour accéder aux variables de session de l'utilisateur
session_start();

// Inclusion du fichier de configuration pour établir la connexion à la base de données
require_once '../appel/pdo.php';

// Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirection vers la page de connexion
    exit(); // Arrête l'exécution du script après la redirection
}

// Vérifie si le formulaire a été soumis en méthode POST et si un ID de moto est présent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $moto_id = $_POST['id']; // Récupère l'ID de la moto à supprimer depuis le formulaire

    // Prépare la requête SQL pour supprimer la moto seulement si elle appartient à l'utilisateur connecté
    $stmt = $pdo->prepare("DELETE FROM motos WHERE id = ? AND user_id = ?");
    $stmt->execute([$moto_id, $_SESSION['user_id']]); // Exécute la requête avec l'ID de la moto et l'ID de l'utilisateur
}

// Redirige l'utilisateur vers le tableau de bord après la suppression
header("Location: dashboard.php");
exit(); // Arrête le script après la redirection pour éviter toute exécution inutile
?>
