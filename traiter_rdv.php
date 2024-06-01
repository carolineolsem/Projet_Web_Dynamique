<?php
session_start();
$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        die("Vous devez être connecté pour prendre un rendez-vous.");
    }

    $user_id = $_SESSION['id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $description = $_POST['description'];

    $sql = "INSERT INTO rendezvous (user_id, date, heure, description) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("isss", $user_id, $date, $heure, $description);

    if ($stmt->execute()) {
        echo "Rendez-vous pris avec succès.";
        header("Location: rdv.php");
    } else {
        echo "Erreur lors de la prise de rendez-vous : " . $stmt->error;
    }

    $stmt->close();
}

$connexion->close();
?>
