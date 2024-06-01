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

$serveur = "sportify.mysql.database.azure.com"; // Adresse du serveur MySQL Azure
$utilisateur = "ece"; // Nom d'utilisateur MySQL
$motdepasse = "Sportify!"; // Mot de passe MySQL
$basededonnees = "sportify"; // Nom de la base de données MySQL
$port = 3306; // Replace with your MySQL server's port if it's not 3306
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

$horaires = [
    "lundi" => null,
    "mardi" => null,
    "mercredi" => null,
    "jeudi" => null,
    "vendredi" => null,
    "samedi" => null,
    "dimanche" => null
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["activite"])) {
        $activite = $_POST["activite"];

        $sql = "
            SELECT 
                utilisateurs.nom AS nom_coach, 
                utilisateurs.prenom AS prenom_coach, 
                utilisateurs.email, 
                coachs.specialite, 
                coachs.photo, 
                coachs.cv, 
                coachs.id,
                disponibilites.jour, 
                disponibilites.heure_debut, 
                disponibilites.heure_fin
            FROM 
                coachs
            JOIN 
                utilisateurs ON coachs.utilisateur_id = utilisateurs.id
            JOIN 
                coachs_activites ON coachs.id = coachs_activites.coach_id
            JOIN 
                activites_sportives ON coachs_activites.activite_id = activites_sportives.id
            JOIN 
                disponibilites ON coachs.id = disponibilites.coach_id
            WHERE 
                activites_sportives.nom = ?
        ";

        // Préparer et exécuter la requête
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("s", $activite);
        $stmt->execute();
        $result = $stmt->get_result();

        // Vérifier si des résultats ont été trouvés
        if ($result->num_rows > 0) {
            // Stocker les horaires
            while ($row = $result->fetch_assoc()) {
                $jour = strtolower($row["jour"]);
                $horaires[$jour] = $row["heure_debut"] . "<br>-<br>" . $row["heure_fin"];
                $coach_info = $row;
            }
        } else {
            echo "Aucun coach trouvé pour cette activité.";
        }

        // Fermer la connexion
        $stmt->close();
    }
}
$connexion->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV - Sportify</title>
    <link rel="stylesheet" href="styles_coach.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <style>

    </style>
</head>
<body>
<header>
    <h1>Sportify</h1>
    <nav class="navbar navbar-expand-md">
        <img class="navbar-brand" src="imgs/logo.png" alt="logo" style="width:100px;">
        <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="../parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="../recherche.php">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="../RDV.php">RDV</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $connected ? '../account.php' : '../login.html'; ?>">
                        <?php echo $connected ? $prenom : 'Connexion'; ?>
                    </a>
                </li>
        </div>
    </nav>
</header>
<body>
<div class="coach">
    <div class="coach-info">
        <div class="coach-photo">
            <?php
            if ($coach_info && $coach_info['photo']) {
                //echo '<img src=". $coach_info['photo'] . " alt="Photo du coach">';
                echo "<img src='" . $coach_info['photo'] . "'>";
                //echo '<img src="../imgs/coachs/guy_dumais.jpg" alt="Photo du coach">';
            }
            ?>
        </div>
        <div class="coach-details">
            <?php
            if ($coach_info) {
                echo "<h2>" . $coach_info["prenom_coach"] . " " . $coach_info["nom_coach"] . "</h2>";
                echo "<p>Email: " . $coach_info["email"] . "</p>";
                echo "<p>Spécialité: " . $coach_info["specialite"] . "</p>";
                echo "<p><a href='" . $coach_info["cv"] . "' target='_blank'>Voir le CV</a></p>";
            }
            ?>

        </div>
    </div>
    <div class="horraires">
        <table class="table table-bordered">
            <tr>
                <th>Lundi</th>
                <th>Mardi</th>
                <th>Mercredi</th>
                <th>Jeudi</th>
                <th>Vendredi</th>
                <th>Samedi</th>
                <th>Dimanche</th>
            </tr>
            <tr>
                <?php
                foreach ($horaires as $jour => $horaire) {
                    if ($horaire) {
                        echo "<td class='disponible'>$horaire</td>";
                    } else {
                        echo "<td class='non-disponible'>Non disponible</td>";
                    }
                }
                ?>
            </tr>
        </table>
        <a href="../prendre_RDV.php?coach_id=<?php echo $coach_info['id']; ?>" class="btn btn-primary RDV" style="background-color: #39c82d; border-color: #39c82d">Prendre RDV</a>        <a href="../chat/get_all_messages.php?coach_id=<?php echo $coach_info['id']; ?>" class="btn btn-secondary CHAT" style="background-color: #0c76af; border-color: #0c76af">Communiquer</a>
    </div>
</div>
</body>
</html>
