<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: login.html");
    exit();
}

// Récupérer les informations de l'utilisateur à partir de la session
$id = $_SESSION['id'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

// GET ALL INFOS FROM USER (NOM PRENOM EMAIL)
$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

$sql = "SELECT nom, prenom, email FROM utilisateurs WHERE id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nom, $prenom, $email);
    $stmt->fetch();
} else {
    echo "Utilisateur introuvable.";
    exit();
}

// GET type_utilisateur
$sql = "SELECT type_utilisateur FROM utilisateurs WHERE id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($type_utilisateur);
    $stmt->fetch();
} else {
    echo "Utilisateur introuvable.";
    exit();
}



?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV - Sportify</title>
    <link rel="stylesheet" href="coachs/styles_coach.css">
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
        <img class="navbar-brand" src="imgs/logo.png" alt="logo" style="width:40px;">
        <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="accueil.html">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="Recherche.html">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="RDV.html">RDV</a></li>
                <li class="nav-item"><a class="nav-link" href="account.html"><?php echo $nom ?></a></li>
            </ul>
        </div>
    </nav>
</header>
<body>
    <div class="coach">
    <h1>Bienvenue, <?php echo $prenom . ' ' . $nom; ?>!</h1>
    <!-- IF ADMIN, SHOW EVERYTHING -->
    <?php if ($type_utilisateur == 'administrateur') { ?>
        <h2>Vous êtes un administrateur.</h2>
        <a href="admin_users.php">Gérer les utilisateurs</a>
        <a href="admin_coachs.php">Gérer les coachs</a>
    <?php } ?>
    <!-- IF COACH, SHOW COACH PAGE -->
    <?php if ($type_utilisateur == 'coach') { ?>
        <h2>Vous êtes un coach.</h2>
        <a href="coachs/coach.php">Voir votre page de coach</a>
    <?php } ?>
    <!-- IF USER, SHOW USER PAGE -->
    <?php if ($type_utilisateur == 'client') { ?>
        <h2>Vous êtes un utilisateur.</h2>
        <a href="users/user.php">Voir votre page d'utilisateur</a>
    <?php } ?>

    </div>
</div>
</body>
</html>


<!-- Votre autre HTML ici -->
</body>
</html>