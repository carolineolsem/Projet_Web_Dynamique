<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

// Connexion à la base de données
//$serveur = "sportify.mysql.database.azure.com";
//$utilisateur = "ece";
//$motdepasse = "Sportify!";
//$basededonnees = "sportify";
//$port = 3306;

//$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

//if ($connexion->connect_error) {
//    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
//}

// Identifier le nom de la base de données
$database = "Sportify";

// Se connecter à la base de données
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);


// Vérifier que les données POST sont présentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['expediteur_id']) && isset($_POST['destinataire_id']) && isset($_POST['contenu'])) {
    $expediteur_id = $_POST['expediteur_id'];
    $destinataire_id = $_POST['destinataire_id'];
    $contenu = $_POST['contenu'];
    $type_message = 'courriel'; // On peut adapter cela selon les besoins

    // Insérer le message dans la base de données
    $sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, type_message) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    if ($stmt === false) {
        die("Erreur lors de la préparation de la requête : " . $connexion->error);
    }
    $stmt->bind_param("iiss", $expediteur_id, $destinataire_id, $contenu, $type_message);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Message envoyé']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'envoi du message']);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
}

$connexion->close();
?>
