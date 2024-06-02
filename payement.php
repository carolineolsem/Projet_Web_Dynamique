<?php
// ENREGISTREMENT DES DONNEES DE PAIEMENT DANS LE SCHEMA paiements

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}

$connected = true;
$id = $_SESSION['id'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

$nom = $_POST['card_name'];
$numero_carte = $_POST['card_number'];
$date_expiration = $_POST['expiry_date'] . "-01";
$code_securite = $_POST['security_code'];
$payment_type = $_POST['payment_type'];

$montant = 100;

//TRANSFORM DATE FORMAT FROM MM/YY TO YYYY-MM-DD

$sql = "INSERT INTO paiements (client_id, nom_sur_carte, type_carte, numero_carte, date_expiration, code_securite, montant) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("issssis", $id, $nom, $payment_type, $numero_carte, $date_expiration, $code_securite, $montant);
$stmt->execute();

$stmt->close();
$connexion->close();

header("Location: RDV.php");
?>