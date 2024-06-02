<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../login.html");
}

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Connection failed: " . $connexion->connect_error);
}

$expediteur_id = $_POST['expediteur_id'];
$destinataire_id = $_POST['destinataire_id'];
$contenu = $_POST['contenu'];

// Récupérer la date et l'heure actuelles et ajouter 2 heures
$date_envoi = date('Y-m-d H:i:s', strtotime('+2 hours'));

$sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi) VALUES (?, ?, ?, ?)";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("iiss", $expediteur_id, $destinataire_id, $contenu, $date_envoi);
$stmt->execute();

header("Location: get_all_messages.php?coach_id=" . $destinataire_id);
?>