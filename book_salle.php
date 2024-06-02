<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['id'];
$coach_id = $_POST['coach_id'];
$day = $_POST['day'];
$time = $_POST['time'];
$end_time = date('H:i', strtotime($time) + 3600);

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

// calculer la date du rendez-vous
$today = new DateTime();
$today->setTime(0, 0, 0);
$dayOfWeek = date('N', strtotime($day));
$daysUntilNextDayOfWeek = ($dayOfWeek - $today->format('N') + 7) % 7;
$date = $today->add(new DateInterval('P' . $daysUntilNextDayOfWeek . 'D'));


$sql = "INSERT INTO rendez_vous (client_id, coach_id, date_rdv, heure_debut, jour, heure_fin) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $connexion->prepare($sql);
$format = $date->format('Y-m-d');
$stmt->bind_param("iissss", $user_id, $coach_id, $format, $time, $day, $end_time);
$stmt->execute();

//GET NAME OF COACH AND SPECIALITY
$sql = "SELECT id, specialite FROM coachs WHERE id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $coach_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($coach_user_id,$speciality);
$stmt->fetch();


$sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi, type_message) VALUES (?, ?, ?, NOW(), 'texto')";
$stmt = $connexion->prepare($sql);
$admin_id = 0;  // ID de l'administrateur

$welcomeMessage = "Votre séance de " . $speciality ." a été enregistré pour le " . $day . " à " . $time . ". Merci de votre confiance.";
$stmt->bind_param("iis", $admin_id, $user_id, $welcomeMessage);
$stmt->execute();

$stmt->close();
$connexion->close();

header("Location: payement_form.php");
exit;
?>