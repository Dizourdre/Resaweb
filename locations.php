<?php

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resaweb_mayhem";


$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Définir l'encodage des caractères en UTF-8
mysqli_set_charset($conn, "utf8");

// Récupération des noms de mondes à partir de la base de données
$sql_quartiers = "SELECT nom_quartier FROM quartiers";
$sql_types = "SELECT nom_type FROM types";
$result_types = mysqli_query($conn, $sql_types);
$result_quartiers = mysqli_query($conn, $sql_quartiers);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="shortcut icon" href="img/logo_nav.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Locations</title>
    <link rel="stylesheet" href="locations.css">
</head>
<body>
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
    <div class="filters">
        <img class=bg_filters src="img/filters.png">

        <?php
                // Affichage de la liste déroulante avec les noms de mondes
                echo '<form method="GET">';
                echo '<label class="nom_filtre, label_quartiers" for="quartier">Quartier : <br></label>';
                echo '<select class=quartiers name="quartier" id="quartier">';
                echo '<option value="Tous">Tous</option>';
                while ($row = mysqli_fetch_assoc($result_quartiers)) {
                    echo '<option value="'.$row['nom_quartier'].'">'.$row['nom_quartier'].'</option>';
                }
                echo '</select>';

                // Affichage de la liste déroulante avec les noms des types 
                echo '<label class="nom_filtre, label_type" for="type">Type : <br></label>';
                echo '<select class=type name="type" id="type">';
                echo '<option value="Tous">Tous</option>';
                echo '<option value="Appartement">Appartement</option>'; 
                echo '<option value="Maison">Maison</option>'; 
                echo '<option value="Chambre">Chambre</option>'; 
                echo '<option value="Suite">Suite</option>'; 
                echo '</select>';
        ?>

            <label class=label_prix> Prix : </label>
            <div class="price">
                <input class=ipt_prix type="radio" name="prix" id="<100" value="Moins de 100 €$">
                <label for="<100" class=Prix>Moins de 100 €$ </label>  
                <input class=ipt_prix type="radio" name="prix" id="100-999" value="Entre 100 et 999 €$">
                <label for="100-999" class=Prix > Entre 100 et 999 €$ </label>
                <input class=ipt_prix type="radio" name="prix" id="1000-9999" value="Entre 1000 et 9999 €$">
                <label for="1000-9999" class=Prix>Entre 1000 et 9999 €$</label>
                <input class=ipt_prix type="radio" name="prix" id=">10000" value="Plus de 10000 €$">
                <label for=">10000" class=Prix>Plus de 10000 €$</label>
            </div>

            <input type="submit" name="submit" class="recherche_location" value="Rechercher">
        </form>
    </div>

    <?php

        if (isset($_GET["quartier"])) {
            $recherche = $_GET["quartier"];
        

        if ($recherche == 'Tous') {
            $sql_recherche = "SELECT id_location, nom_location, image_location, description_location, prix_location FROM locations";
        } else {
             $sql_recherche = "SELECT id_location, nom_location, image_location, prix_location,  description_location FROM locations
                JOIN quartiers ON locations.id_quartier = quartiers.id_quartier JOIN types ON locations.id_type = types.id_type
                WHERE quartiers.nom_quartier = '$recherche'";
        }

        if (isset($_GET['prix'])) {
            $recherche_prix = $_GET['prix'];
            if ($recherche_prix == 'Moins de 100 €$') {
                if (strpos($sql_recherche, 'WHERE') !== false) {
                    $sql_recherche .= " AND prix_location < 100";
                } else {
                    $sql_recherche .= " WHERE prix_location < 100";
                }
            } elseif ($recherche_prix == 'Entre 100 et 999 €$') {
                if (strpos($sql_locations, 'WHERE') !== false) {
                    $sql_recherche .= " AND prix_location >= 100 AND prix_location <= 999";
                } else {
                    $sql_recherche .= " WHERE prix_location >= 100 AND prix_location <= 999";
                }
            } elseif ($recherche_prix == 'Entre 1000 et 9999 €$') {
                if (strpos($sql_recherche, 'WHERE') !== false) {
                    $sql_recherche .= " AND prix_location >= 1000 AND prix_location <= 9909";
                } else {
                    $sql_recherche .= " WHERE prix_location >= 1000 AND prix_location <= 9999";
                }
            } elseif ($recherche_prix == 'Plus de 10000 €$') {
                if (strpos($sql_recherche, 'WHERE') !== false) {
                    $sql_recherche .= " AND prix_location > 10000";
                } else {
                    $sql_recherche .= " WHERE prix_location > 10000";
                }
            }
        }



            $result_recherche = mysqli_query($conn, $sql_recherche);
            echo '<div class=boites>';
            while ($row = mysqli_fetch_assoc($result_recherche)) {
                echo '<div class=box_location>';
                echo '<h3 class=titre_annonce>' . $row['nom_location'] . '</h3>';
                echo '<img src="' . $row['image_location'] . '">';
                echo "<a class='offre' href='annonces.php?id=" . $row['id_location'] . "'>Voir l'annonce ➔</a>";
                echo '<p>' . $row['description_location'] . '</p>';
                echo '<h4>' . $row['prix_location']. ' €$ </h4>';
                echo '</div>';
            }
            echo '</div>';
        }

    // Traitement du formulaire soumis
        if (isset($_GET['submit'])) {
            // Récupération du monde sélectionné
            if (isset($_GET['quartier'])) {
                $quartier = $_GET['quartier'];
            }
            if (isset($_GET['prix'])) {
                $prix = $_GET['prix'];
            }
            // Récupération du type sélectionné
            if (isset($_GET['type'])) {
                $type = $_GET['type'];
            }
        

            // Récupération des locations pour ce monde à partir de la base de données
            if ($quartier == 'Tous') {
                $sql_locations = "SELECT id_location, nom_location, image_location, description_location, prix_location FROM locations";
            } else {
                $sql_locations = "SELECT id_location, nom_location, image_location, prix_location,  description_location FROM locations
                    JOIN quartiers ON locations.id_quartier = quartiers.id_quartier JOIN types ON locations.id_type = types.id_type
                    WHERE quartiers.nom_quartier = '$quartier'";
            }

            if (isset($_GET['prix'])) {
                $prix = $_GET['prix'];
                if ($prix == 'Moins de 100 €$') {
                    if (strpos($sql_locations, 'WHERE') !== false) {
                        $sql_locations .= " AND prix_location < 100";
                    } else {
                        $sql_locations .= " WHERE prix_location < 100";
                    }
                } elseif ($prix == 'Entre 100 et 999 €$') {
                    if (strpos($sql_locations, 'WHERE') !== false) {
                        $sql_locations .= " AND prix_location >= 100 AND prix_location <= 999";
                    } else {
                        $sql_locations .= " WHERE prix_location >= 100 AND prix_location <= 999";
                    }
                } elseif ($prix == 'Entre 1000 et 9999 €$') {
                    if (strpos($sql_locations, 'WHERE') !== false) {
                        $sql_locations .= " AND prix_location >= 1000 AND prix_location <= 9909";
                    } else {
                        $sql_locations .= " WHERE prix_location >= 1000 AND prix_location <= 9999";
                    }
                } elseif ($prix == 'Plus de 10000 €$') {
                    if (strpos($sql_locations, 'WHERE') !== false) {
                        $sql_locations .= " AND prix_location > 10000";
                    } else {
                        $sql_locations .= " WHERE prix_location > 10000";
                    }
                }
            }

            $result_locations = mysqli_query($conn, $sql_locations);
            $nb_resultats = mysqli_num_rows($result_locations);

            // Affichage des locations pour ce monde

            echo '<div class=boites>';
            while ($row = mysqli_fetch_assoc($result_locations)) {
                echo '<div class=box_location>';
                echo '<h3 class=titre_annonce>' . $row['nom_location'] . '</h3>';
                echo '<img src="' . $row['image_location'] . '">';
                echo "<a class='offre' href='annonces.php?id=" . $row['id_location'] . "'>Voir l'annonce ➔</a>";
                echo '<p>' . $row['description_location'] . '</p>';
                echo '<h4>' . $row['prix_location']. ' €$ </h4>';
                echo '</div>';
            }
            echo '</div>';
        }
                // Fermeture de la connexion à la base de données
                mysqli_close($conn);
        ?>
</body>

</html>