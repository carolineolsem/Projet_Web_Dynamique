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

$coach_id = $_GET['coach_id'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

// Fetch coach availability
$sql_availability = "SELECT jour, heure_debut, heure_fin FROM disponibilites WHERE coach_id = ?";
$stmt_availability = $connexion->prepare($sql_availability);
$stmt_availability->bind_param("i", $coach_id);
$stmt_availability->execute();
$stmt_availability->bind_result($jour, $heure_debut, $heure_fin);

$availability = [];
while ($stmt_availability->fetch()) {
    $availability[$jour][] = ['start' => $heure_debut, 'end' => $heure_fin];
}
$stmt_availability->close();

// Fetch booked appointments
$sql_bookings = "SELECT jour, heure_debut, heure_fin FROM rendez_vous WHERE coach_id = ?";
$stmt_bookings = $connexion->prepare($sql_bookings);
$stmt_bookings->bind_param("i", $coach_id);
$stmt_bookings->execute();
$stmt_bookings->bind_result($jour_booking, $heure_debut_booking, $heure_fin_booking);

$bookings = [];
while ($stmt_bookings->fetch()) {
    $bookings[$jour_booking][] = ['start' => $heure_debut_booking, 'end' => $heure_fin_booking];
}
$stmt_bookings->close();

$connexion->close();

function isTimeSlotBooked($day, $time, $bookings) {
    if (!isset($bookings[$day])) return false;
    foreach ($bookings[$day] as $booking) {
        $bookingStart = new DateTime($booking['start']);
        $bookingEnd = new DateTime($booking['end']);
        $timeSlot = new DateTime($time);
        if ($timeSlot >= $bookingStart && $timeSlot < $bookingEnd) {
            return true;
        }
    }
    return false;
}

function generateTimeSlots($start, $end, $interval = '1 hour') {
    $startTime = new DateTime("08:00");
    $endTime = new DateTime("18:01");
    $timeSlots = [];

    while ($startTime < $endTime) {
        $timeSlots[] = $startTime->format('H:i');
        $startTime->modify("+$interval");
    }
    return $timeSlots;
}

$days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
$allTimeSlots = [];
foreach ($availability as $day => $timeRanges) {
    foreach ($timeRanges as $timeRange) {
        $timeSlots = generateTimeSlots($timeRange['start'], $timeRange['end']);
        $allTimeSlots = array_unique(array_merge($allTimeSlots, $timeSlots));
    }
}
sort($allTimeSlots);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre RDV - Sportify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="prendre_RDV.css">
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

<main class="container" style="width: 80%; height: 50%">
    <table class="table" style="height: 50%">
        <thead>
        <tr>
            <th>Horaire</th>
            <?php foreach ($days as $day): ?>
                <th><?php echo ucfirst($day); ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($allTimeSlots as $timeSlot): ?>
            <tr>
                <td><?php echo $timeSlot; ?></td>
                <?php foreach ($days as $day): ?>
                    <?php
                    $isAvailable = isset($availability[$day]) && array_reduce($availability[$day], function ($carry, $item) use ($timeSlot) {
                            return $carry || ($timeSlot >= $item['start'] && $timeSlot < $item['end']);
                        }, false);
                    $isBooked = $isAvailable ? isTimeSlotBooked($day, $timeSlot, $bookings) : false;
                    ?>
                    <td class="<?php echo $isAvailable ? ($isBooked ? 'reserve' : 'disponible') : 'non-disponible'; ?>">
                        <?php if ($isAvailable && !$isBooked): ?>
                            <form action="book_salle.php" method="post">
                                <input type="hidden" name="day" value="<?php echo $day; ?>">
                                <input type="hidden" name="time" value="<?php echo $timeSlot; ?>">
                                <input type="hidden" name="coach_id" value="<?php echo $coach_id; ?>">
                                <button type="submit" class="btn" style="background-color: #7bd373">Disponible</button>
                            </form>
                        <?php else: ?>
                            <?php echo $isBooked ? 'Réservé' : ''; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>