<?php
session_start();

// Vérifiez si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['id']) || $_SESSION['type_utilisateur'] != 'administrateur') {
    // Si l'utilisateur n'est pas connecté ou n'est pas un administrateur, redirigez-le vers la page de connexion
    header("Location: login.php");
    exit();
}

// Récupérez l'ID de l'utilisateur à supprimer à partir de l'URL
$id_to_delete = $_GET['id'];

// Connexion à la base de données
$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

// Supprimez l'utilisateur de la base de données
$sql = "DELETE FROM utilisateurs WHERE id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $id_to_delete);

if ($stmt->execute()) {
    header("Location: admin_users.php");
} else {
    echo "Erreur lors de la suppression de l'utilisateur : " . $stmt->error;
}

$stmt->close();
$connexion->close();
?>