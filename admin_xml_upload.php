<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['type_utilisateur'] != 'administrateur') {
    // If the user is not logged in or is not an admin, redirect them to the login page
    header("Location: login.php");
    exit();
}

$utilisateur_id = $_GET['utilisateur_id'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
        $coach_id = $_POST['coach_id'];

        $sql = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("i", $coach_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $coach = $result->fetch_assoc();

        $filename = $coach['prenom'] . "_" . $coach['nom'] . ".xml";
        $filepath = "coachs/" . $filename;
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $filepath)) {
            header("Location: admin_xml.php?utilisateur_id=$utilisateur_id");        }
        else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        echo "Aucun fichier n'a été téléchargé.";
    }
}

$connexion->close();
?>