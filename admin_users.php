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

// Récupérer tous les utilisateurs
$sql = "SELECT * FROM utilisateurs";
$result = $connexion->query($sql);

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
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            // Afficher chaque utilisateur avec des boutons pour supprimer ou contacter
            while($row = $result->fetch_assoc()) {
                $id = $row["id"];
                echo '<div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                ' . $row["prenom"] . ' ' . $row["nom"] . '
                            </div>
                            <div class="card-body">
                                <p>Email: ' . $row["email"] . '</p>
                                <p>Type: ' . $row["type_utilisateur"] . '</p>
                                <p>Date de création: ' . $row["date_creation"] . '</p>
                                <button class="btn btn-danger" onclick="location.href=\'admin_delete_user.php?id=' . $id . '\'">Supprimer</button>
                                <button class="btn btn-success" onclick="location.href=\'chat/get_all_messages.php?utilisateur_id=' . $id . '\'">Contacter</button>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo "<p class='text-center'>Aucun utilisateur trouvé.</p>";
        }
        $connexion->close();
        ?>
    </div>
</div>

<footer>
    <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</body>
</html>
