<?php
session_start();

if (!isset($_SESSION['id'])) $connected = false;
else {
    $connected = true;
    $id = $_SESSION['id'];
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tout Parcourir - Sportify</title>
   <link rel="stylesheet" href="stylesheet.css">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
   <script>
       $(document).ready(function() {
           $('#lienRecherche').click(function(e) {
               e.preventDefault();
               $('#barreRecherche').show();
           });
       });
   </script>
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
                <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="recherche.php">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="RDV.php">RDV</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $connected ? 'account.php' : 'login.html'; ?>">
                        <?php echo $connected ? $prenom : 'Connexion'; ?>
                    </a>
                </li>
        </div>
    </nav>
</header>

<div id="barreRecherche" class="barre-recherche">
    <h1>Faites votre recherche</h1>
    <form action="barre_recherche.php" method="POST">
        <label for="recherche">Recherche:</label>
        <input type="text" id="recherche" name="recherche" placeholder="coach, spécialité, salle" required>
        <button type="submit" name="rechercher">Rechercher</button>
    </form>
</div>
</body>
<footer>
    <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</html>
