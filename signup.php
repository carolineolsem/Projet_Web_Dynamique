<?php
$serveur = "sportify.mysql.database.azure.com";
$utilisateur = "ece";
$motdepasse = "Sportify!";
$basededonnees = "sportify";
$port = 3306;

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees, $port);

if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Vérifier que tous les champs sont remplis
    if (empty($email) || empty($nom) || empty($prenom) || empty($password1) || empty($password2)) {
        echo "Tous les champs doivent être remplis.";
        exit();
    }

    if ($password1 != $password2) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    $sql = "SELECT id FROM utilisateurs WHERE email = ?";
    $stmt = $connexion->prepare($sql);
    if ($stmt === false) {
        die("Erreur lors de la préparation de la requête : " . $connexion->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Un compte avec cet email existe déjà.";
    } else {
        $stmt->close();

        $sql = "INSERT INTO utilisateurs (email, nom, prenom, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $role = "client";
        $stmt->bind_param("sssss", $email, $nom, $prenom, $password1, $role);
        $stmt->execute();

        //GET BACK ID
        $sql = "SELECT id FROM utilisateurs WHERE email = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        $stmt->fetch();


        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $sql = "SELECT id FROM utilisateurs WHERE email = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();
            $last_inserted_id = $connexion->insert_id;  // Get the last inserted id here

            // Envoi d'un email de bienvenue
            // ...

            $sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi, type_message) VALUES (?, ?, ?, NOW(), 'texto')";
            $stmt = $connexion->prepare($sql);
            $admin_id = 1;  // ID de l'administrateur

            $welcomeMessage = "Bienvenue sur Sportify, $prenom $nom! Nous sommes ravis de vous avoir parmi nous.";
            $stmt->bind_param("iis", $admin_id, $id, $welcomeMessage);
            $stmt->execute();

            header("Location: login.html");
            echo "success";
        } else {
            echo "Erreur lors de la création du compte.";
        }
    }

    $stmt->close();
}
else {
    echo "Where are you trying to go?";
}

$connexion->close();
?>