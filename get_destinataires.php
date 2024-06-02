<?php
// Connexion à la base de données
$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}



// Fonction pour récupérer la liste des destinataires disponibles
function getDestinataires($type_utilisateur) {
    global $connexion;
    $destinataires = array();

    // Sélectionnez les utilisateurs en fonction du type (clients ou coachs)
    $sql = "SELECT id, nom, prenom FROM utilisateurs WHERE type_utilisateur = '$type_utilisateur'";
    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $destinataires[] = array(
                "id" => $row["id"],
                "nom" => $row["nom"],
                "prenom" => $row["prenom"]
            );
        }
    }

    return $destinataires;
}

// Exemple d'utilisation :
// Récupérer la liste des clients
$clients = getDestinataires('client');
// Récupérer la liste des coachs
$coachs = getDestinataires('coach');
?>
