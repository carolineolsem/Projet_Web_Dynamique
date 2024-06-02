<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../login.html");
}
if (isset($_GET['coach_id'])) {
    $cible_id = $_GET['coach_id'];
    // Vous pouvez maintenant utiliser $coach_id
}

//utilisateur_id=
if (isset($_GET['utilisateur_id'])) {
    $cible_id = $_GET['utilisateur_id'];
    // Vous pouvez maintenant utiliser $coach_id
}

// Récupérer les informations de l'utilisateur à partir de la session
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
    die("Connection failed: " . $connexion->connect_error);
}

if (isset($_GET['coach_id'])) {
    $cible_id = $_GET['coach_id'];
    $sql = "SELECT nom, prenom FROM utilisateurs WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("i", $cible_id);
    $stmt->execute();
    $stmt->bind_result($nom_cible, $prenom_cible);
    $stmt->fetch();
    $stmt->free_result();
}

if (isset($_GET['utilisateur_id'])) {
    $cible_id = $_GET['utilisateur_id'];
    $sql = "SELECT nom, prenom FROM utilisateurs WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("i", $cible_id);
    $stmt->execute();
    $stmt->bind_result($nom_cible, $prenom_cible);
    $stmt->fetch();
    $stmt->free_result();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV - Sportify</title>
    <link rel="stylesheet" href="../coachs/styles_coach.css">
    <link rel="stylesheet" href="styles.css">
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
                <li class="nav-item"><a class="nav-link" href="../accueil.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="../parcourir.php">Tout Parcourir</a></li>
                <li class="nav-item"><a class="nav-link" href="../recherche.php">Recherche</a></li>
                <li class="nav-item"><a class="nav-link" href="../RDV.php">RDV</a></li>
                <li class="nav-item"><a class="nav-link" href="../account.php"><?php echo $prenom ?></a></li>
            </ul>
        </div>
    </nav>
</header>
<body style="justify-content: center; background-color: #F2F5F8">
<div class="mega_chat" style="justify-content: center">
<div class="chat">
    <div class="chat-history">
        <ul>
        <?php
        // SHOW MESSAGE BY ORDER OF TIME, LEFT FOR RECEIVED, RIGHT FOR SENT

        /* Example of received message
        <li>
                <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:12 AM, Today</span>
                </div>
                <div class="message my-message">
                    Are we meeting today? Project has been already finished and I have results to show you.
                </div>
            </li>
        */

        // Example of sent message
        /*
        <li class="clearfix">
                <div class="message-data align-right">
                    <span class="message-data-time" >10:10 AM, Today</span> &nbsp; &nbsp;
                    <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>

                </div>
                <div class="message other-message float-right">
                    Hi Vincent, how are you? How is the project coming along?
                </div>
            </li>
        */

        // Récupérer les messages de la base de données
        $sql = "SELECT * FROM messages WHERE (expediteur_id = ? AND destinataire_id = ?) OR (expediteur_id = ? AND destinataire_id = ?) ORDER BY date_envoi ";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("iiii", $id, $cible_id, $cible_id, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Parcourir les messages et générer le HTML pour chaque message
        while($row = $result->fetch_assoc()) {
            if ($row['expediteur_id'] == $id) {
                // Message envoyé
                echo '<li class="clearfix">
            <div class="message-data align-right">
                <span class="message-data-time">' . $row['date_envoi'] . ', Today</span> &nbsp; &nbsp;
                <span class="message-data-name">' . $prenom . '</span> <i class="fa fa-circle me"></i>
            </div>
            <div class="message other-message float-right">' . $row['contenu'] . '</div>
        </li>';
            }
            else {
                // Message reçu
                echo '<li>
            <div class="message-data">
                <span class="message-data-name">' . $prenom_cible . '</span> <i class="fa fa-circle me"></i>
                <span class="message-data-time">' . $row['date_envoi'] . ', Today</span>
            </div>
            <div class="message my-message">' . $row['contenu'] . '</div>
        </li>';
            }
        }

        $stmt->free_result(); // Free the result set

        ?>

        </ul>
    </div>
    <div class="chat-message clearfix">
        <form action="send_message.php" method="post">
            <input type="hidden" name="expediteur_id" value="<?php echo $id; ?>">
            <input type="hidden" name="destinataire_id" value="<?php echo $cible_id; ?>">
            <textarea name="contenu" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
            <button type="submit" id="send">Send</button>
        </form
    </div>
</div>
</body>
</html>
