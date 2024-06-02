<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['type_utilisateur'] != 'administrateur') {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

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



$sql = "SELECT * FROM utilisateurs WHERE id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $utilisateur_id);
$stmt->execute();
$result = $stmt->get_result();
$coach = $result->fetch_assoc();

$filename = $coach['prenom'] . "_" . $coach['nom'] . ".xml";
$filepath = "coachs/" . $filename;

$connexion->close();

function display_xml($xml, $level = 0) {
    foreach ($xml->children() as $child) {
        echo str_repeat('&nbsp;', $level * 4) . "<strong>" . strtoupper($child->getName()) . ":</strong> " . $child . "<br>";
        if ($child->count() > 0) {
            display_xml($child, $level + 1);
            echo "<br>"; // Ajouter une ligne vide après chaque enfant

        }
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV - Sportify</title>
    <link rel="stylesheet" href="admin_interface.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
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

<div class="container">
    <?php
    if (file_exists($filepath)) {
        $xml = simplexml_load_file($filepath);
        echo "<h2>Informations du coach</h2>";

        if ($xml === false) {
            echo "Échec du chargement du fichier XML.";
            foreach(libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
        } else {
            echo "<h1>Contenu du fichier XML</h1>";
            echo "<div class='container'>";
            echo "<div class='row'>";

            // Afficher le contenu du fichier XML
            echo "<div class='col-md-12'>";
            echo "<div class='card'>";
            echo "<div class='card-body'>";
            display_xml($xml);
            echo "</div>";
            echo "</div>";
            echo "</div>";

            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "
            <p> '$filename' n'existe pas.</p>
            <h2>Upload XML</h2>
            <form action='admin_xml_upload.php?utilisateur_id=$utilisateur_id' method='post' enctype='multipart/form-data'>            Select XML file to upload: 
            <input type='file' name='fileToUpload' id='fileToUpload'>
            <input type='hidden' name='coach_id' value='$utilisateur_id'>
           
            <input type='submit' value='Upload XML' name='submit'>
          </form>";
    }
    ?>

</div>