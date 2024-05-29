<?php

$serveur = "sportify.mysql.database.azure.com"; // Adresse du serveur MySQL
$utilisateur = "ece"; // Nom d'utilisateur MySQL
$motdepasse = "Sportify!"; // Mot de passe MySQL
$basededonnees = "tst"; // Nom de la base de données MySQL
$port = 3306; // Replace with your MySQL server's port if it's not 3306
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}


// Rest of your code...
// RENVOIE LA LISTE DES AVIOSN (ID, NAME)
    $sql = "SELECT * FROM tst.avions";
    $resultat = $connexion->query($sql);
    if ($resultat->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>NAME</th></tr>";
        while ($ligne = $resultat->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $ligne["id"] . "</td>";
            echo "<td>" . $ligne["name"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        // echo in php console
        echo "<script>console.log('Liste des avions affichée.');</script>";


    }
