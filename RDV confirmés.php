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

$client_id = $id;

$sql_confirmed = "SELECT rdv.id, rdv.date_rdv, rdv.heure_debut, rdv.heure_fin, u.nom AS coach_nom, u.prenom AS coach_prenom, a.adresse, a.digicode, a.nom AS sport, c.salle
                  FROM rendez_vous rdv
                  JOIN coachs c ON rdv.coach_id = c.id
                  JOIN utilisateurs u ON c.utilisateur_id = u.id
                  JOIN activites_sportives a ON c.specialite = a.nom
                  WHERE rdv.client_id = ? AND rdv.statut = 'confirmé'";

$stmt_confirmed = $connexion->prepare($sql_confirmed);
$stmt_confirmed->bind_param("i", $client_id);
$stmt_confirmed->execute();
$result_confirmed = $stmt_confirmed->get_result();

$sql_historique = "SELECT rdv.id AS id_his, rdv.date_rdv AS date_rdv_his, rdv.heure_debut AS heure_debut_his, rdv.heure_fin AS heure_fin_his, u.nom AS coach_nom_his, u.prenom AS coach_prenom_his, a.adresse AS adresse_his, a.digicode AS digicode_his, a.nom AS sport_his, c.salle AS salle_his
                   FROM rendez_vous rdv
                   JOIN coachs c ON rdv.coach_id = c.id
                   JOIN utilisateurs u ON c.utilisateur_id = u.id
                   JOIN activites_sportives a ON c.specialite = a.nom
                   WHERE rdv.client_id = ? AND (rdv.statut = 'annulé' OR rdv.statut = 'terminé')";

$stmt_historique = $connexion->prepare($sql_historique);
$stmt_historique->bind_param("i", $client_id);
$stmt_historique->execute();
$result_historique = $stmt_historique->get_result();

$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>RDV - Sportify</title>
   <link rel="stylesheet" href="styles.css">
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
<body>
<div id="RDV" class="container">
    <br/>
<h1>Rendez-vous confirmés</h1>

<?php if ($result_confirmed->num_rows > 0): ?>
    <?php while($row = $result_confirmed->fetch_assoc()): ?>
    <br/>
        <div class="rdv-container">
            <div class="rdv-header">Rendez-vous avec <?php echo $row['coach_prenom'] . ' ' . $row['coach_nom']; ?></div>
            <div class="rdv-details">Salle du coach: <?php echo $row['salle']; ?></div>
            <div class="rdv-details">Sport: <?php echo $row['sport']; ?></div>
            <div class="rdv-details">Date: <?php echo $row['date_rdv']; ?></div>
            <div class="rdv-details">Heure: <?php echo $row['heure_debut'] . ' - ' . $row['heure_fin']; ?></div>
            <div class="rdv-details">Lieu de rendez-vous: <?php echo $row['adresse']; ?></div>
            <div class="rdv-details">Digicode: <?php echo $row['digicode']; ?></div>
            <form method="post" action="RDV%20annuler.php">
                <input type="hidden" name="rdv_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="cancel-button">Annulation de RDV</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucun rendez-vous confirmé.</p>
<?php endif; ?>
    <br/>
</div>

<div id="Historique" class="container">
<h1>Historique des rendez-vous</h1>

<?php if ($result_historique->num_rows > 0): ?>
    <?php while($row = $result_historique->fetch_assoc()): ?>
    <br/>
        <div class="rdv-container">
            <div class="rdv-header">Rendez-vous avec <?php echo $row['coach_prenom_his'] . ' ' . $row['coach_nom_his']; ?></div>
            <div class="rdv-details">Salle du coach: <?php echo $row['salle_his']; ?></div>
            <div class="rdv-details">Sport: <?php echo $row['sport_his']; ?></div>
            <div class="rdv-details">Date: <?php echo $row['date_rdv_his']; ?></div>
            <div class="rdv-details">Heure: <?php echo $row['heure_debut_his'] . ' - ' . $row['heure_fin_his']; ?></div>
            <div class="rdv-details">Lieu de rendez-vous: <?php echo $row['adresse_his']; ?></div>
            <div class="rdv-details">Digicode: <?php echo $row['digicode_his']; ?></div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucun rendez-vous dans l'historique.</p>
<?php endif; ?>
    <br/>
</div>
</body>
<footer>
        <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</html>
