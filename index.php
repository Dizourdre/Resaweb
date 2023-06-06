<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resaweb_mayhem";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

$sql_quartiers = "SELECT nom_quartier FROM quartiers";
$result_quartiers = mysqli_query($conn, $sql_quartiers);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="shortcut icon" href="img/logo_nav.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le nom du site la</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header> 
      <nav id="menu">
        <input type="checkbox" id="responsive-menu">
        <label for="responsive-menu" class="menu-toggle"></label>
        <ul>
          <li class="accueil"><a href="index.php"><img class="logo_nav" src="img/logo_nav.png" alt="Retour à l'accueil"></a></li>
          <li class="olalanon"><h1>NIGHSTAY RENTALS</h1></li>
          <li><a href="locations.php?quartier=Tous&type=Tous&submit=Rechercher">Locations</a></li>
          <li><a href="Apropos.html">À Propos</a></li>
        </ul>
      </nav>
          <div class="recherche">
            <h2> Où cherchez-vous ? </h2>
            <h2> Type de logement</h2>
            <h2> Pour quel budget ? (à la nuit)</h2>

            <ul>
              <form method="GET" action="locations.php">
                <input id=tout type="radio" name="prix">
                <label for="tout"> Tous les prix</label><br>
                <input id=un_chiffre type="radio" value="Moins de 100 €$" name="prix">
                <label for="un_chiffre"> Moins de 100 €$</label><br>
                <input id=deux_chiffre type="radio" value="Entre 100 et 999 €$" name="prix">
                <label for="deux_chiffre"> Entre 100 et 999 €$</label><br>
                <input id=trois-chiffre type="radio" value="Entre 1000 et 9999 €$" name="prix">
                <label for="trois-chiffre"> Entre 1000 et 999 €$</label><br>
                <input id=quatre-chiffre type="radio" value="Plus de 10000 €$"name="prix">
                <label for="quatre-chiffre"> Plus de 10000 €$</label><br>
              </ul>
                <button class="rechercher"> Rechercher </button> 

                <?php 
                  echo '<select class=zone_texte_1 name="quartier" id="quartier">';
                  echo '<option value="Tous">Tous</option>';
                  while ($row = mysqli_fetch_assoc($result_quartiers)) {
                      echo '<option value="'.$row['nom_quartier'].'">'.$row['nom_quartier'].'</option>';
                  }
                  echo '</select>';
                ?>
                <input class=zone_texte_2 type="text" placeholder="Chambre d'hôtel, appartement..." id="logement">
              </form>  


          

            <div class="separator">
              <p> </p>
            </div>
          </div>

          <div class="separator2">
            <p> </p>
          </div>
    </header>

  <main>
    <div class="category"> 
      <div class=background>
      <h2> Nouveautés</h2>  
    </div>
    </div>

    <div class="js-slider">
      <img class="left" src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Feather-arrows-arrow-left.svg/2048px-Feather-arrows-arrow-left.svg.png" alt="Flèche Gauche">
      <img class="right" src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Feather-arrows-arrow-left.svg/2048px-Feather-arrows-arrow-left.svg.png" alt="Flèche Droite">
      <div class="js-boxes">
      <?php

        
            // Récupération des trois dernières annonces
            $sql = "SELECT * FROM locations ORDER BY id_location DESC LIMIT  6";
            $result = mysqli_query($conn, $sql);

            // Affichage des annonces dans le slider
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="box">'; 
                echo '<img src="' . $row['image_location'] . '">';
                echo '<hr>';
                echo '<h1 class="Prix">' . $row['prix_location'] . ' €$</h1>';
                echo "<a href='annonces.php?id=" . $row['id_location'] . "' class='Bouton'>Voir l'annonce ➔</a>";
                echo "<h2>" . $row['nom_location'] . "</h2>";
                echo "<p class=desc_new>" . $row['description_location'] . "</p>";
                echo '</div>';
            }


            // Fermeture de la connexion à la base de données
            mysqli_close($conn);
            ?>
      </div> 
    </div>


  </main>
  <footer>
      <div class="colonne"> 
        <h3> Nos Réseaux </h3> 
        <a href="https://fr-fr.facebook.com"><img class=reseau src="img/Facebook.svg" alt="Page Facebook"><p> Facebook </p></a>
        <a href="https://www.instagram.com"><img class=reseau src="img/Instagram.svg" alt="Page Instagram"><p> Instagram</p></a>
        <a href="https://twitter.com/home"><img class=reseau src="img/Twitter.svg" alt="Page Twitter"> <p> Twitter</p></a>
        <a href="https://www.youtube.com"><img class=reseau src="img/Youtube.svg" alt="Chaîne Youtube"><p>Youtube</p></a>
      </div>
      <div class="colonne"> 
        <h3> A Propos </h3>
        <a href="Apropos.html"><p> NightStay Rentals c'est quoi ? </p></a>
        <a href="Mentions.html"><p > Mentions Légales</p></a>
      </div>
      <div class="colonne"> 
        <a href="index.php" class="exclude"><img class="logo_footer" src="img/logo_nav.png" alt="Retour en haut"></a>
      </div>
      
    </footer>
</body>
<script src="script.js"></script>
</html>