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

mysqli_set_charset($conn, "utf8");


if (isset($_GET["id"])) {
    $locationId = $_GET["id"];
    $locationQuery = "SELECT nom_location, prix_location FROM locations WHERE id_location = $locationId";
    $result = mysqli_query($conn, $locationQuery);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nomlocation = $row['nom_location'];
            $prixlocation = $row['prix_location'];
        }
    }
}

if (isset($_POST['submit'])) {
    // Récupérer les valeurs soumises du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $dateArrivee = $_POST['date_arrivee'];
    $nombreNuits = $_POST['nb_nuits'];
    $location = $nomlocation;
    $prix = $prixlocation * $nombreNuits;

    // Requête SQL pour insérer les données dans la table appropriée
    $sql = "INSERT INTO reservation (id_reservation, id_location, nom, mail, telephone, nb_nuits, date_arrivee)
            VALUES (NULL , '$locationId','$nom', '$email', '$telephone', '$nombreNuits', '$dateArrivee')";

    if (mysqli_query($conn, $sql)) {
        echo "<p> Réservation enregistrée avec succès.</p>";
    } else {
        echo "Erreur lors de l'enregistrement de la réservation : " . mysqli_error($conn);
    }

            $to = $email; // Adresse e-mail de location
            $subject = "Confirmation de réservation";
            $message = "
            <html>
                <head>
                <style>
                    body {
                        color: #00ff00;
                        font-family: 'Arial', sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        background-color: #000;
                        padding: 20px;
                    }
                </style>
                </head>
                <body>
                <div class='container'>
                <h1>Confirmation de votre réservation pour $location !</h1>
                <p>Merci d'avoir réservé votre sommeil sur notre site $nom ! Vous avez réservé une ou des nuits à partir du $dateArrivee pour une durée de $nombreNuits nuits. Le montant à régler le jour de votre arrivée sera de $prix €$.</p>
                <a href='http://resaweb.lesault.butmmi.o2switch.site/index.php' class='button'>Visiter le site</a>
                </div>
                </body>
                </html>
            ";
            
            // En-têtes de l'e-mail
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: resaweb@lesault.butmmi.o2switch.site" . "\r\n";

            if (mail($to, $subject, $message, $headers)) {
                echo "Un e-mail de confirmation a été envoyé à $email.";
            } else {
                echo "Erreur lors de l'envoi de l'e-mail de confirmation.";
            }
        } else {
            echo "Erreur lors de l'enregistrement de la réservation : " . mysqli_error($conn);
    }


// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Réservation</title>
    <link rel="stylesheet" href="formulaire_reservation.css">
</head>
<body>
<header> 
<nav id="menu">
        <input type="checkbox" id="responsive-menu">
        <label for="responsive-menu" class="menu-toggle"></label>
        <ul>
          <li class="accueil"><a class=lien_nav href="index.php"><img class="logo_nav" src="img/logo_nav.png" alt="Retour à l'accueil"></a></li>
          <li><h1 class=titre_nav>LE NOM DU SITE ICI</h1></li>
          <li><a href="locations.php?quartier=Tous&type=Tous&submit=Rechercher">Locations</a></li>
        </ul>
      </nav>
    </header>

<h1>Réservation</h1>

<div class="formulaire">
    <form method="POST" action="">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
    
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
    
        <label for="telephone">Téléphone :</label>
        <input type="tel" name="telephone" id="telephone" required>
    
        <label for="date_arrivee">Date d'arrivée :</label>
        <input type="date" name="date_arrivee" id="date_arrivee" required>
    
        <label for="nombre_personnes">Nombre de nuits louées :</label>
        <input type="number" name="nb_nuits" id="nb_nuits" required>
    
        <input type="submit" name="submit" value="Réserver">
    </form>
</div>
</body>

</html>