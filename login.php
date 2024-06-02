<?php

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, nom, prenom, type_utilisateur FROM utilisateurs WHERE email = ? AND mot_de_passe = ?";
    $stmt = $connexion->prepare($sql);
    if ($stmt === false) {
        die("Erreur lors de la préparation de la requête : " . $connexion->error);
    }
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nom, $prenom, $type_utilisateur);
        $stmt->fetch();
        $_SESSION['id'] = $id;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['type_utilisateur'] = $type_utilisateur;
        header("Location: account.php");
        exit();
    } else {
        echo "Email ou mot de passe incorrect.";
    }

    $stmt->close();
}

$connexion->close();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['id'])) {
    echo "Bonjour, " . $_SESSION['nom'] . " " . $_SESSION['prenom'];
}
?>