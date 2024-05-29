<?php
$serveur = "sportify.mysql.database.azure.com"; // Adresse du serveur MySQL Azure
$utilisateur = "ece"; // Nom d'utilisateur MySQL
$motdepasse = "Sportify!"; // Mot de passe MySQL
$basededonnees = "sportify"; // Nom de la base de données MySQL
$port = 3306; // Remplacez par le port de votre serveur MySQL si ce n'est pas 3306

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

// Vérifier si l'ID du rendez-vous est fourni
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rdv_id'])) {
    $rdv_id = $_POST['rdv_id'];

    // Mettre à jour le statut du rendez-vous à "annulé"
    $sql = "UPDATE rendez_vous SET statut = 'annulé' WHERE id = $rdv_id";
    if ($connexion->query($sql) === TRUE) {
        echo "<p>Rendez-vous annulé avec succès.</p>";
    } else {
        echo "<p>Erreur lors de l'annulation du rendez-vous : " . $connexion->error . "</p>";
    }
}

$client_id = 5;

$sql = "SELECT rdv.id, rdv.date_rdv, rdv.heure_debut, rdv.heure_fin, u.nom AS coach_nom, u.prenom AS coach_prenom, c.salle
        FROM rendez_vous rdv
        JOIN coachs c ON rdv.coach_id = c.id
        JOIN utilisateurs u ON c.utilisateur_id = u.id
        WHERE rdv.client_id = $client_id AND rdv.statut = 'confirmé'";

$result = $connexion->query($sql);
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
        <img class="navbar-brand" src="logo.png" alt="logo" style="width:40px;">
        <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.html">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="parcourir.html">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="recherche.html">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="rendezvous.html">RDV</a></li>
                <li class="nav-item"><a class="nav-link" href="compte.html">Connexion</a></li>
            </ul>
        </div>
    </nav>
</header>
<body>
<h1>Rendez-vous Confirmés</h1>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="rdv-container">
            <div class="rdv-header">Rendez-vous avec <?php echo $row['coach_prenom'] . ' ' . $row['coach_nom']; ?></div>
            <div class="rdv-details">Date: <?php echo $row['date_rdv']; ?></div>
            <div class="rdv-details">Heure: <?php echo $row['heure_debut'] . ' - ' . $row['heure_fin']; ?></div>
            <div class="rdv-details">Salle: <?php echo $row['salle']; ?></div>
            <div class="rdv-details">Téléphone:</div>
            <div class="rdv-details">Document demandé: [Insérer Document]</div>
            <div class="rdv-details">Digicode: [Insérer Digicode]</div>
            <form method="post" action="">
                <input type="hidden" name="rdv_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="cancel-button">Annulation de RDV</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucun rendez-vous confirmé.</p>
<?php endif; ?>

</body>
<footer>
    <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</html>

<?php
$connexion->close();
?>



