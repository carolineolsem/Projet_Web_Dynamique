<?php
session_start();

if (!isset($_SESSION['id'])) $connected = false;
else {
    $connected = true;
    // Récupérer les informations de l'utilisateur à partir de la session
    $id = $_SESSION['id'];
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
}

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

$sql = "SELECT coachs.salle AS salle, coachs.specialite AS activite, coachs.id AS id FROM coachs";
$result = $connexion->query($sql);
$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV - Sportify</title>
    <link rel="stylesheet" href="salles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700|Roboto:400,700&display=swap">
    <style>

    </style>
</head>
<body>
<header>
    <h1>Sportify</h1>
    <nav class="navbar navbar-expand-md">
        <img class="navbar-brand" src="imgs/logo.png" alt="logo" style="width:40px;">
        <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="recherche.php">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="RDV.php">RDV</a></li>
                <li class="nav-item"><a class="nav-link" href="account.php"><?php echo $prenom ?></a></li>
            </ul>
        </div>
    </nav>
</header>
<div class="table-container">
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table'><tr><th>Salle</th><th>Coach</th><th>Activité</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["salle"]. "</td><td>" . $row["activite"]. "</td>";
            echo "<td><a href='prendre_RDV_salle.php?coach_id=" . $row["id"] . "'>Réserver</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='text-center'>Aucune salle disponible.</p>";
    }
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
