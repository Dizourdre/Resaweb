<?php

$idAnnonce = $_GET['id']; // ID de l'annonce à afficher
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resaweb_mayhem"; 
$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Connexion échouée: " . mysqli_connect_error());
}
 
mysqli_set_charset($conn, "utf8");
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="shortcut icon" href="img/logo_nav.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de l'appartement</title>
    <link rel="stylesheet" href="annonces.css">
</head>
<body>
    <header> 
    <nav id="menu">
        <input type="checkbox" id="responsive-menu">
        <label for="responsive-menu" class="menu-toggle"></label>
        <ul>
          <li class="accueil"><a href="index.php"><img class="logo_nav" src="img/logo_nav.png" alt="Retour à l'accueil"></a></li>
          <li class="olalanon"><h1 class=titre_nav>NIGHSTAY RENTALS</h1></li>
          <li><a href="locations.php?quartier=Tous&type=Tous&submit=Rechercher">Locations</a></li>
          <li><a href="Apropos.html">À Propos</a></li>
        </ul>
      </nav>
    </header>


    <?php
    if (isset($_GET["id"])) {
    $locationId = $_GET["id"];

    // Récupération des informations de la destination depuis la base de données
    $sql = "SELECT * FROM locations WHERE id_location = '$locationId'";
    $result = mysqli_query($conn, $sql);

    // Vérification si la destination existe dans la base de données
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

         // Génération des informations de la page en utilisant les données récupérées
        echo '<div id="container">';
        echo "<h1>" . $row["nom_location"] . "</h1>";
        echo '<div id="image-container">';
        echo '<img src="' . $row['image_location'] . '">';
        echo '</div>';
        echo '<div id="description">';
        echo "<p>" . $row["description_location"] . "</p>";
        echo '</div>';
        echo "</div>";
        echo '</div>';
        echo "<div class='separator2'>";
        echo "<p> </p>";
        echo '</div>';
        echo '<a id="reserve-button" href="formulaire_reservation.php?id=' . $idAnnonce . '">Réserver ➔</a>';
        echo '<p id="prix-button">' . $row['prix_location'] .' €$</p>';
        echo "<div class='separator'>";
        echo "<p> </p>";
        echo '</div>';
        echo '<h2> Prix pour une nuit :</h2>';

    }
  }

?>
  </body>
</html>