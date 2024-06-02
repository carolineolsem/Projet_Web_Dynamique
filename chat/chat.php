<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../login.html");
}
if (isset($coach_info['id'])) {
    $cible_id = $coach_info['coach_id'];
    $cible_id = 3;

    // Vous pouvez maintenant utiliser $coach_id
}

$cible_id = 3;

// Récupérer les informations de l'utilisateur à partir de la session
$id = $_SESSION['id'];
$id = 1;
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

// Messages sent order by time
$sql = "SELECT * FROM messages WHERE (expediteur_id = ? AND destinataire_id = ?) ORDER BY date_envoi ";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("ii", $id, $cible_id);
echo $id, $cible_id;
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
        echo $row['contenu'];
    }
}
else echo "No messages found";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatbox</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>
</head>
<body>
<div class="container clearfix">
    <div class="people-list" id="people-list">
        <div class="search">
            <input type="text" placeholder="search" />
            <i class="fa fa-search"></i>
        </div>
        <ul class="list">
            <!-- Liste des utilisateurs, à remplir dynamiquement -->
            <!-- APPELER fetch_users_talked_to.php POUR REMPLIR CETTE LISTE -->

        </ul>
    </div>
    <div class="chat">
        <div class="chat-header clearfix">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar" />
            <div class="chat-about">
                <div class="chat-with">Chat with Vincent Porter</div>
                <div class="chat-num-messages">already 1 902 messages</div>
            </div>
            <i class="fa fa-star"></i>
        </div>
        <div class="chat-history">
            <ul>
                <div class="message other-message float-right">
                    Hi Vincent, how are you? How is the project coming along?
                </div>            </ul>
        </div>
        <div class="chat-message clearfix">
            <textarea name="message-to-send" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
            <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
            <i class="fa fa-file-image-o"></i>
            <button>Send</button>
        </div>
    </div>
</div>
<script id="message-template" type="text/x-handlebars-template">
    <li class="clearfix">
        <div class="message-data align-right">
            <span class="message-data-time">{{time}}, Today</span> &nbsp; &nbsp;
            <span class="message-data-name">Olia</span> <i class="fa fa-circle me"></i>
        </div>
        <div class="message other-message float-right">{{messageOutput}}</div>
    </li>
</script>
<script id="message-response-template" type="text/x-handlebars-template">
    <li>
        <div class="message-data">
            <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
            <span class="message-data-time">{{time}}, Today</span>
        </div>
        <div class="message my-message">{{response}}</div>
    </li>
</script>
<script src="chat.js"></script>
</body>
</html>
