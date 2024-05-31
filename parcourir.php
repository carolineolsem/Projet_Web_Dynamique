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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Parcourir - Sportify</title>
    <link rel="stylesheet" href="../Projet_Web_Dynamique/parcourir.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
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
                    <a class="nav-link" href="<?php echo $connected ? 'account.php' : 'login.php'; ?>">
                        <?php echo $connected ? $prenom : 'Connexion'; ?>
                    </a>
                </li>
        </div>
    </nav>
</header>
<main>
    <div class="container features">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Activités Sportives</h3>
                <a href="#activites-sportives">
                    <img src="imgs/tout-parcourir/Activites_sportives.jpg" class="img-fluid feature-image" alt="Activités Sportives">
                </a>
                <p>
                    Toutes les activités sportives dont les membres de l’Omnes Education peuvent participer.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Les Sports de compétition</h3>
                <a href="#sports-competition">
                    <img src="imgs/tout-parcourir/Sport%20compétition.png" class="img-fluid feature-image" alt="Sports de compétition">
                </a>
                <p>
                    Toutes les catégories de sport compétitif dans Sportify.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Salle de sport Omnes</h3>
                <a href="#salle-de-sport-omnes">
                    <img src="imgs/tout-parcourir/SALLE.png" class="img-fluid feature-image" alt="Salle de sport Omnes">
                </a>
                <p>
                    Toutes les catégories de sport compétitif dans Sportify.
                </p>
            </div>
        </div>
    </div>

    <div id="activites-sportives" class="container features">
        <H1> ACTIVITES SPORTIVES </H1>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Musculation</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Musculation">
                        <img src="imgs/tout-parcourir/musculation.jpg" alt="Musculation">
                    </button>
                </form>
                <p>
                    Découvrez nos sessions de musculation adaptées à tous les niveaux.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Fitness</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Fitness">
                        <img src="imgs/tout-parcourir/fitness.jpg" alt="Fitness">
                    </button>
                </form>
                <p>
                    Participez à nos cours de fitness pour améliorer votre forme physique.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Biking</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Biking">
                        <img src="imgs/tout-parcourir/biking.jpg" alt="Biking">
                    </button>
                </form>
                <p>
                    Rejoignez nos sessions de biking pour une expérience cardio intense.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Cardio-Training</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Cardio-Training">
                        <img src="imgs/tout-parcourir/cardio.jpg" alt="Cardio-Training">
                    </button>
                </form>
                <p>
                    Améliorez votre endurance avec nos entraînements de cardio-training.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Cours Collectifs</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Cours Collectifs">
                        <img src="imgs/tout-parcourir/collective.jpg" alt="Cours Collectifs">
                    </button>
                </form>
                <p>
                    Participez à nos cours collectifs pour une expérience de groupe motivante.
                </p>
            </div>
        </div>
    </div>
    <div id="sports-competition" class="container features">
        <H1> COMPETITIONS SPORTIVES </H1>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Basketball</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Basketball">
                        <img src="imgs/tout-parcourir/basketball.jpg" alt="Basketball">
                    </button>
                </form>
                <p>
                    Rejoignez nos équipes de basketball pour des entraînements et compétitions passionnantes.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Football</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Football">
                        <img src="imgs/tout-parcourir/football.jpg" alt="Football">
                    </button>
                </form>
                <p>
                    Participez à nos sessions de football et développez vos compétences sur le terrain.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Rugby</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Rugby">
                        <img src="imgs/tout-parcourir/rugby.jpg" alt="Rugby">
                    </button>
                </form>
                <p>
                    Découvrez l'intensité du rugby avec nos équipes dédiées.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Tennis</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Tennis">
                        <img src="imgs/tout-parcourir/tennis.jpg" alt="Tennis">
                    </button>
                </form>
                <p>
                    Rejoignez nos cours de tennis pour tous les niveaux.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Natation</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Natation">
                        <img src="imgs/tout-parcourir/natation.jpg" alt="Natation">
                    </button>
                </form>
                <p>
                    Améliorez vos compétences en natation avec nos coachs expérimentés.
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h3 class="feature-title">Plongeon</h3>
                <form action="coachs/coach.php" method="post">
                    <button type="submit" name="activite" value="Plongeon">
                        <img src="imgs/tout-parcourir/plongeon.jpg" alt="Plongeon">
                    </button>
                </form>
                <p>
                    Découvrez le monde du plongeon avec nos sessions dédiées.
                </p>
            </div>
        </div>
    </div>
    <div id="salle-de-sport-omnes" class="container">
        <h1>Salle de sport Omnes</h1>
        <div id="services-disponibles">
            <h2>Services disponibles</h2>
            <p>
                Nous offrons une gamme complète de services pour répondre à tous vos besoins en matière de fitness, y compris des entraîneurs personnels, des cours de groupe, et des équipements de pointe.
            </p>
        </div>
        <div id="regles-utilisation">
            <h2>Règles d'utilisation</h2>
            <ul>
                <li>Porter des chaussures de sport appropriées en tout temps.</li>
                <li>Nettoyer les équipements après chaque utilisation.</li>
                <li>Respecter les autres membres et le personnel.</li>
                <li>Ne pas monopoliser les équipements.</li>
                <li>Apporter une serviette personnelle.</li>
            </ul>
        </div>
        <div id="horaire-gym">
            <h2>Horaire de la gym</h2>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Jour</th>
                    <th>Heures d'ouverture</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Lundi - Vendredi</td>
                    <td>06:00 - 22:00</td>
                </tr>
                <tr>
                    <td>Samedi</td>
                    <td>08:00 - 20:00</td>
                </tr>
                <tr>
                    <td>Dimanche</td>
                    <td>10:00 - 18:00</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="questionnaires">
            <h2>Questionnaires pour les nouveaux utilisateurs</h2>
            <p>
                Nous demandons à tous les nouveaux utilisateurs de remplir un questionnaire de santé et de forme physique avant d'utiliser nos installations. Vous pouvez <a href="questionnaire.html">télécharger le questionnaire ici</a>.
            </p>
        </div>
        <div id="coordonnees-responsables">
            <h2>Coordonnées des responsables</h2>
            <p>
                Pour toute question ou assistance, veuillez contacter notre équipe :
            </p>
            <ul>
                <li><strong>Responsable de la salle de sport :</strong> Jean Dupont - <a href="mailto:jean.dupont@example.com">jean.dupont@example.com</a></li>
                <li><strong>Entraîneur en chef :</strong> Marie Curie - <a href="mailto:marie.curie@example.com">marie.curie@example.com</a></li>
                <li><strong>Service clientèle :</strong> 01 23 45 67 89</li>
            </ul>
        </div>
    </div>
</main>
<footer>
    <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</body>
</html>

