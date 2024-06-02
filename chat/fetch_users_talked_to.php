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

// Obtenir les utilisateurs avec qui l'utilisateur a parlé
$sql = "SELECT DISTINCT expediteur_id, destinataire_id FROM messages WHERE expediteur_id = ? OR destinataire_id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("ii", $id, $id);
$stmt->execute();
$result = $stmt->get_result();

$people_ids = [];
while ($row = $result->fetch_assoc()) {
    if ($row['expediteur_id'] != $id) {
        $people_ids[] = $row['expediteur_id'];
    }
    if ($row['destinataire_id'] != $id) {
        $people_ids[] = $row['destinataire_id'];
    }
}

// Récupérer les informations des utilisateurs
$people_ids = array_unique($people_ids);
$people_info = [];
foreach ($people_ids as $user_id) {
    $sql = "SELECT id, nom, prenom FROM utilisateurs WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $people_info[] = $row;
    }
}

echo json_encode($people_info);

$stmt->close();
$connexion->close();
?>
