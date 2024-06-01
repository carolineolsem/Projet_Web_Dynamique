<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$id = $_SESSION['id'];
$cible_id = $_POST['cible_id'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Connection failed: " . $connexion->connect_error);
}

$sql = "SELECT * FROM messages WHERE (expediteur_id = ? AND destinataire_id = ?) OR (expediteur_id = ? AND destinataire_id = ?)";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("iiii", $id, $cible_id, $cible_id, $id);
$stmt->execute();
$result = $stmt->get_result();

$messages = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);

$stmt->close();
$connexion->close();
?>
