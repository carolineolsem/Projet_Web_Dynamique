<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['type_utilisateur'] != 'administrateur') {
    header("Location: login.php");
    exit();
}

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
    die("Please fill in all fields.");
}

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Failed to connect to the database: " . $connexion->connect_error);
}

$sql = "SELECT id FROM utilisateurs WHERE email = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("This email is already used.");
}

$stmt->close();

$sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, 'administrateur')";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("ssss", $nom, $prenom, $email, $password);

if ($stmt->execute()) {
    header("Location: admin_users.php");
} else {
    echo "Error creating the coach: " . $stmt->error;
}

$stmt->close();
$connexion->close();
?>