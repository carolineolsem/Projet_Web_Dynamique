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
    <link rel="stylesheet" href="accountstyle.css">
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
                <li class="nav-item"><a class="nav-link" href="account.php"><?php echo $prenom ?></a></li>
            </ul>
        </div>
    </nav>
</header>
    <div class="user_info">
    <h1>Bienvenue, <?php echo $prenom . ' ' . $nom; ?>!</h1>

    <?php if ($type_utilisateur == 'administrateur') { ?>
        <h2>Vous êtes un administrateur.</h2>
        <p> Nom : <?php echo $nom; ?> </p>
        <p> Prénom : <?php echo $prenom; ?> </p>
        <p> Email : <?php echo $email; ?> </p>
        <a class="nav-link btn btn-info" style="margin-bottom: 5px" href="admin_users.php">Gérer les utilisateurs</a>
        <a class="nav-link btn btn-info" href="admin_coachs.php">Gérer les coachs</a>
        <a class="nav-link btn btn-info" href="admin_form_admin.php">Ajouter un administrateur</a>
    <?php } ?>
    <?php if ($type_utilisateur == 'coach') { ?>
        <h2>Vous êtes un coach.</h2>
        <p> Nom : <?php echo $nom; ?> </p>
        <p> Prénom : <?php echo $prenom; ?> </p>
        <p> Email : <?php echo $email; ?> </p>

    <?php } ?>

    <?php if ($type_utilisateur == 'client') { ?>
        <h2>Vous êtes un client.</h2>
        <p> Nom : <?php echo $nom; ?> </p>
        <p> Prénom : <?php echo $prenom; ?> </p>
        <p> Email : <?php echo $email; ?> </p>
    <?php }
    ?>

        <br>    <a class="nav-link btn btn-danger" href="logout.php">Logout</a>
    </div>

    <?php if ($type_utilisateur == 'coach') { ?>
    <h2 style="padding-bottom: 20px; padding-top: 20px; text-align: center;">Rendez-vous</h2>
        <!-- LISTE DES RDV DU COACH -->
                <ul class="list">
                    <!-- Liste des rendez-vous, à remplir dynamiquement -->
                    <!-- APPELER fetch_users_talked_to.php POUR REMPLIR CETTE LISTE -->
                    <?php
                    // Obtenir l'id du coach a partir de l'utilisateur_id
                    $sql = "SELECT id FROM coachs WHERE utilisateur_id = ?";
                    $stmt = $connexion->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $coach_id = $row['id'];

                    // Obtenir les rendez-vous du coach
                    $sql = "SELECT client_id, date_rdv, heure_debut, heure_fin FROM rendez_vous WHERE coach_id = ?";
                    $stmt = $connexion->prepare($sql);
                    $stmt->bind_param("i", $coach_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rdv_info = [];
                    while ($row = $result->fetch_assoc()) {
                        $rdv_info[] = $row;
                    }


                    foreach ($rdv_info as $rdv) {
                        $sql = "SELECT nom, prenom FROM utilisateurs WHERE id = ?";
                        $stmt = $connexion->prepare($sql);
                        $stmt->bind_param("i", $rdv['client_id']);
                        $stmt->execute();
                        $resultCLIENT = $stmt->get_result();
                        $client_info = $resultCLIENT->fetch_assoc();

                        if ($client_info && array_key_exists('prenom', $client_info) && array_key_exists('nom', $client_info)) {
                            echo '<div class="rdv_info" style="border: 1px solid #ccc; padding: 20px; background-color: #FAF0CA; color: #333; font-size: 18px;">';
                            echo '<p>'. $client_info['prenom'] . ' ' . $client_info['nom'] . '</p>';
                            echo '<p>Le: ' . $rdv['date_rdv'] . '</p>';
                            echo '<p>'. $rdv['heure_debut'] . ' à ' . $rdv['heure_fin'] . '</p>';
                            echo '</div>';
                        }
                    }

                    ?>

                </ul>
    <?php }
    ?>



<h2 style="padding-bottom: 20px; padding-top: 20px; text-align: center;">Messages</h2>
    <div class="container clearfix" style="width: 30%">
        <div class="people-list" id="people-list" ">
            <div class="search" style="width: 65%">
                <input type="text" placeholder="search" />
                <i class="fa fa-search"></i>
            </div>
            <ul class="list">
                <!-- Liste des utilisateurs, à remplir dynamiquement -->
                <!-- APPELER fetch_users_talked_to.php POUR REMPLIR CETTE LISTE -->
                <?php
                // Obtenir les utilisateurs avec qui l'utilisateur a parlé
                $sql = "SELECT DISTINCT expediteur_id, destinataire_id FROM messages WHERE expediteur_id = ? OR destinataire_id = ?";
                $stmt = $connexion->prepare($sql);
                $stmt->bind_param("ii", $id, $id);
                $stmt->execute();
                $result = $stmt->get_result();

                $people_ids = [];
                while ($row = $result->fetch_assoc()) {
                    if ($row['expediteur_id'] != $id) {
                        $people_ids[] = $row['expediteur_id'];
                    }
                    if ($row['destinataire_id'] != $id) {
                        $people_ids[] = $row['destinataire_id'];
                    }
                }

                // Récupérer les informations des utilisateurs
                $people_ids = array_unique($people_ids);
                $people_info = [];
                foreach ($people_ids as $user_id) {
                    $sql = "SELECT id, nom, prenom, type_utilisateur FROM utilisateurs WHERE id = ?";
                    $stmt = $connexion->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $people_info[] = $row;
                    }
                }

                // Afficher les utilisateurs

                foreach ($people_info as $person) {
                    // Définir la classe CSS en fonction du type d'utilisateur
                    $user_class = '';
                    if ($person['type_utilisateur'] == 'coach') {
                        $user_class = 'user-green';
                    } elseif ($person['type_utilisateur'] == 'administrateur') {
                        $user_class = 'admin-red';
                    } else {
                        $user_class = 'user-grey';
                    }

                    echo '<li class="clearfix" style="width: 50%;" onclick="redirectToChat(' . $person['id'] . ')">
        <div class="about">
            <div class="name ' . $user_class . '">' . $person['prenom'] . ' ' . $person['nom'] . '</div>
            <div class="status">
                <i class="fa fa-circle online"></i> <div>' . $person['type_utilisateur'] . '</div>
            </div>
        </div>
    </li>';
                }
                ?>

            </ul>
        </div>
</div>
<script type="text/javascript">
    function redirectToChat(userId) {
        window.location.href = 'chat/get_all_messages.php?utilisateur_id=' + userId;
    }
</script>
</body>
<footer>
    <p>&copy; 2024 Sportify. Tous droits réservés.</p>
</footer>
</html>

