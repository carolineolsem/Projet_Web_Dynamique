<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}

$connected = true;
$id = $_SESSION['id'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre RDV - Sportify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="prendre_RDV.css">
    <style>

        .container {
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 50px auto;
        }
        header h1 {
        }
        .form-group label {
            font-weight: bold;
        }
        .form-column {
            padding: 10px;
        }
        .form-column input,
        .form-column select {
            margin-bottom: 10px;
        }
        .navbar {
            margin-bottom: 20px;
        }
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
                <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="recherche.php">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="RDV.php">RDV</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $connected ? 'account.php' : 'login.html'; ?>">
                        <?php echo $connected ? $prenom : 'Connexion'; ?>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<h2 class="text-center">Prendre RDV - Payement de 100 €</h2>
<div class="container">
    <h2 class="text-center">Informations de paiement</h2>
    <form action="payement.php" method="post">
        <div class="form-row">
            <div class="form-column col-md-6">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="address1">Adresse Ligne 1:</label>
                    <input type="text" class="form-control" id="address1" name="address1">
                </div>
                <div class="form-group">
                    <label for="address2">Adresse Ligne 2:</label>
                    <input type="text" class="form-control" id="address2" name="address2">
                </div>
                <div class="form-group">
                    <label for="city">Ville:</label>
                    <input type="text" class="form-control" id="city" name="city">
                </div>
                <div class="form-group">
                    <label for="postal_code">Code Postal:</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code">
                </div>
                <div class="form-group">
                    <label for="country">Pays:</label>
                    <input type="text" class="form-control" id="country" name="country">
                </div>
                <div class="form-group">
                    <label for="phone_number">Numéro de téléphone:</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number">
                </div>
                <div class="form-group">
                    <label for="student_card">Carte Etudiant:</label>
                    <input type="text" class="form-control" id="student_card" name="student_card">
                </div>
            </div>
            <div class="form-column col-md-6">
                <div class="form-group">
                    <label for="payment_type">Type de carte de paiement:</label>
                    <select class="form-control" id="payment_type" name="payment_type">
                        <option value="visa">Visa</option>
                        <option value="mastercard">MasterCard</option>
                        <option value="amex">American Express</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="card_number">Numéro de la carte:</label>
                    <input type="text" class="form-control" id="card_number" name="card_number">
                </div>
                <div class="form-group">
                    <label for="card_name">Nom affiché sur la carte:</label>
                    <input type="text" class="form-control" id="card_name" name="card_name">
                </div>
                <div class="form-group">
                    <label for="expiry_date">Date d’expiration de la carte:</label>
                    <input type="month" class="form-control" id="expiry_date" name="expiry_date">
                </div>
                <div class="form-group">
                    <label for="security_code">Code de sécurité:</label>
                    <input type="text" class="form-control" id="security_code" name="security_code" maxlength="4">
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
