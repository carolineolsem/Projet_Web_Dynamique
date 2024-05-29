<?php
$serveur = "sportify.mysql.database.azure.com"; // Adresse du serveur MySQL Azure
$utilisateur = "ece"; // Nom d'utilisateur MySQL
$motdepasse = "Sportify!"; // Mot de passe MySQL
$basededonnees = "sportify"; // Nom de la base de données MySQL
$port = 3306; // Replace with your MySQL server's port if it's not 3306
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}


// Vérifier si l'ID du rendez-vous est fourni
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rdv_id'])) {
    $rdv_id = $_POST['rdv_id'];

    // Mettre à jour le statut du rendez-vous à "annulé"
    $sql = "UPDATE rendez_vous SET statut = 'annulé' WHERE id = $rdv_id";

    if ($connexion->query($sql) === TRUE) {
        echo "Rendez-vous annulé avec succès.";
    } else {
        echo "Erreur lors de l'annulation du rendez-vous: " . $connexion->error;
    }
}

$connexion->close();

// Rediriger vers la page des rendez-vous confirmés
header("Location: rendez_vous_confirmes.php");
exit;
?>
