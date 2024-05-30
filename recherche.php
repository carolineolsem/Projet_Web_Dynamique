<?php
// Identifier le nom de la base de données
$database = "Sportify";

// Se connecter à la base de données
$db_handle = mysqli_connect('sportify.mysql.database.azure.com', 'ece', 'Sportify!');
$db_found = mysqli_select_db($db_handle, $database);

// Vérifier si la base de données est accessible
if ($db_found) {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['rechercher'])) {
            // Récupérer la valeur de recherche depuis le formulaire
            $recherche = $_POST["recherche"];
            
            // Construire la requête SQL
            $sql = "SELECT u.nom, u.prenom, c.specialite, c.photo, c.cv
                    FROM coachs c
                    JOIN utilisateurs u ON c.utilisateur_id = u.id
                    WHERE u.nom LIKE '%$recherche%' 
                       OR u.prenom LIKE '%$recherche%'
                       OR c.specialite LIKE '%$recherche%'";

            // Exécuter la requête SQL
            $result = mysqli_query($db_handle, $sql);
            
            // Vérifier si des résultats ont été trouvés
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="resultats-recherche">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="resultat">';
                    // Afficher la photo du coach
                    if ($row["photo"]) {
                        echo '<img src="' . $row["photo"] . '" alt="Photo de ' . $row["prenom"] . '" width="100"><br>';
                    }
                    // Afficher le nom et prénom du coach avec la couleur spécifiée
                    echo '<h2 style="color: #007179;">' . $row["prenom"] . ' ' . $row["nom"] . '</h2>';
                    // Afficher la spécialité
                    echo '<p>Spécialité: ' . $row["specialite"] . '</p>';
                    // Afficher la salle
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo "Aucun coach trouvé.";
            }
        }
    }
} else {
    echo "Base de données introuvable.";
}

// Fermer la connexion à la base de données
mysqli_close($db_handle);
?>
