<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$id = $_SESSION['id'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Connection failed: " . $connexion->connect_error);
}

$message = $_POST['message'];
$time = date('Y-m-d H:i:s');
$destinataire_id = $_POST['destinataire_id'];
$type_message = $_POST['type_message'];

$sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi, type_message) VALUES (?, ?, ?, ?, ?)";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("iissi", $id, $destinataire_id, $message, $time, $type_message);
$stmt->execute();

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$connexion->close();
?>
