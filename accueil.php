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
    <title>Accueil - Sportify</title>
    <link rel="stylesheet" href="../Projet_Web_Dynamique/styles.css">
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
                    <a class="nav-link" href="<?php echo $connected ? 'account.php' : 'login.html'; ?>">
                        <?php echo $connected ? $prenom : 'Connexion'; ?>
                    </a>
                </li>
        </div>
    </nav>
</header>

<br/>
<div id="Présentation" class="container">
    <h2>Présentation</h2>
    <p> Bienvenue sur Sportify!
        Sur ce site, vous trouverez toutes les activités sportives que nous proposons, ainsi que les évènements que nous organisons.
        N'hésite pas à prendre rendez-vous avec l'un de nos coachs agréés, ou bien contacte-nous pour plus d'informations!</p>
</div>

<div class="carousel-container">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="imgs/acceuil/image1.jpg" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Equipe de volley de Sportify</h5>
                    <p>Venez découvrir nos jeunes talents au tournoi universitaire de volley!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="imgs/acceuil/image2.jpg" alt="Second slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Equipe de foot de Sportify</h5>
                    <p>Découvrez notre équipe de gagnant.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="imgs/acceuil/image3.jpg" alt="Third slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Equipe de basket de sportify</h5>
                    <p>Assistez aux matchs des gagnants du tournoi universitaire 2024</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<main>
    <section id="evenements">
        <h2>Événements à venir</h2>
        <div class="event">
            <img src="imgs/acceuil/portes_ouvertes.jpg" alt="Portes ouvertes" class="feature-image">
            <div class="event-text">
                <h3>Portes ouvertes de Sportify</h3>
                <p>Rejoignez-nous pour une journée portes ouvertes et découvrez nos installations.</p>
                <p>Date : 5 juin 2024</p>
            </div>
        </div>
        <div class="event">
            <img src="imgs/acceuil/match_rugby.jpg" alt="Match de rugby" class="feature-image">
            <div class="event-text">
                <h3>Match de rugby</h3>
                <p>Match entre Omnes Education et les Visiteurs.</p>
                <p>Date : 10 juin 2024</p>
            </div>
        </div>
        <div class="event">
            <img src="imgs/acceuil/coach_tennis.jpg" alt="Rencontre avec le coach de tennis" class="feature-image">
            <div class="event-text">
                <h3>Rencontre avec le coach de tennis</h3>
                <p>Rencontrez notre coach de tennis et obtenez des conseils personnalisés.</p>
                <p>Date : 15 juin 2024</p>
            </div>
        </div>
    </section>

    <div id="Nous retrouver" class="container">
        <h2>Nous retrouver</h2>
        <p>Vous trouverez nos locaux à l'Ecole Centrale d'Electronique à Paris, comme indiqué sur le plan ci-dessous. Venez nous rencontrer et créer votre dossier sportif!</p>
    </div>


    <section id="Maps">
        <div id="map">
            <!-- Intégration de Google Map -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5250.682300356622!2d2.2894633!3d48.8517047!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Campus%20de%20Paris!5e0!3m2!1sfr!2sfr!4v1716837900534!5m2!1sfr!2sfr" width="400" height="300" referrerpolicy="no-referrer-when-downgrade" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>

</main>
<div id="coordonnees-responsables" class="container">
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

<footer>
    <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</body>
</html>