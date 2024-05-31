<?php
// Identifier le nom de la base de données
$database = "Sportify";

// Se connecter à la base de données
$db_handle = mysqli_connect('sportify.mysql.database.azure.com', 'ece', 'Sportify!');
$db_found = mysqli_select_db($db_handle, $database);

// Vérifier si la base de données est accessible
if ($db_found) {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['rechercher'])) {
            // Récupérer la valeur de recherche depuis le formulaire
            $recherche = $_POST["recherche"];
            
            // Construire la requête SQL
            $sql = "SELECT u.nom, u.prenom, c.specialite, c.photo, c.cv
                    FROM coachs c
                    JOIN utilisateurs u ON c.utilisateur_id = u.id
                    WHERE u.nom LIKE '%$recherche%' 
                       OR u.prenom LIKE '%$recherche%'
                       OR c.specialite LIKE '%$recherche%'";

            // Exécuter la requête SQL
            $result = mysqli_query($db_handle, $sql);
            

        }
    }
}

// Fermer la connexion à la base de données
mysqli_close($db_handle);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV - Sportify</title>
    <link rel="stylesheet" href="recherche.css">
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
                <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="recherche.php">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="RDV.php">RDV</a></li>
                <li class="nav-item"><a class="nav-link" href="login.html">Connexion</a></li>
            </ul>
        </div>
    </nav>
</header>
<body>
    <h1> RESULTATS </h1>
        <?php
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="recherche">';
                // Afficher la photo du coach
                if ($row["photo"]) {
                    echo '<img src="' . $row["photo"] . '" alt="Photo de ' . $row["prenom"] . '" width="100"><br>';
                }
                // Afficher le nom et prénom du coach avec la couleur spécifiée
                echo '<h2 style="color: #007179;">' . $row["prenom"] . ' ' . $row["nom"] . '</h2>';
                // Afficher la spécialité
                echo '<p>Spécialité: ' . $row["specialite"] . '</p>';
                // Afficher la salle
                echo '</div>';
            }
        }
        else {
            echo "Aucun coach trouvé.";
        }
        ?>

</body>
</html>
