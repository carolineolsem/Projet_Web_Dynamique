<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['type_utilisateur'] != 'administrateur') {
    // If the user is not logged in or is not an admin, redirect them to the login page
    header("Location: login.php");
    exit();
}

// Retrieve the form data
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validate the form data
if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
    die("Please fill in all fields.");
}

// Connect to the database
$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Failed to connect to the database: " . $connexion->connect_error);
}

// Check if the email is already used
$sql = "SELECT id FROM utilisateurs WHERE email = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If the email is already used, stop the script and inform the user
    die("This email is already used.");
}

$stmt->close();

// Prepare an SQL statement to insert the new coach into the database
$sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, 'coach')";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("ssss", $nom, $prenom, $email, $password);

// Execute the SQL statement and check if the insertion was successful
if ($stmt->execute()) {
    header("Location: admin_coachs.php");
} else {
    echo "Error creating the coach: " . $stmt->error;
}

$stmt->close();
$connexion->close();
?>