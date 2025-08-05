<?php
// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telemedecine_db";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialiser les variables pour les coordonnées du médecin
$medecin = array();

// Récupérer les coordonnées du médecin à partir de la base de données
$sql = "SELECT Nom, Prenom, Email, Specialite FROM utilisateurs WHERE ID = 14515515"; // Vous devez remplacer "1" par l'ID du médecin connecté
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $medecin = $result->fetch_assoc();
}

// Traitement des données soumises si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] ?? '' === 'POST') {
    // Traitement des données soumises
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $specialite = $_POST['specialite'] ?? '';
    $password = $_POST['password'] ?? '';

    // Mettre à jour les informations du médecin dans la base de données
    $update_sql = "UPDATE utilisateurs SET Nom = '$nom', Prenom = '$prenom', Email = '$email', Specialite = '$specialite' WHERE ID = 14515515"; // Vous devez remplacer "1" par l'ID du médecin connecté
    if ($conn->query($update_sql) === TRUE) {
        echo "";
    } else {
        echo "" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en"> <!-- Changement de la langue en anglais -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title> <!-- Modification du titre en anglais -->
    <style>
        body {
            background-image: url('khouloud.jpg'); /* Chemin de votre image */
            background-size: cover; /* Ajuste la taille de l'image pour couvrir toute la page */
            background-position: center; /* Centre l'image horizontalement et verticalement */
        }


        header {
            background-color: #7E57C2; /* Couleur d'arrière-plan de l'en-tête */
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
        }

        main {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #512DA8; /* Couleur du texte des libellés */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 22px);
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #BA68C8; /* Couleur de bordure */
            border-radius: 4px;
            transition: border-color 0.3s ease, transform 0.3s ease; /* Animation de transition */
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            width: calc(100% - 10px);
            outline: none;
            border-color: #9C27B0; /* Couleur de bordure lorsqu'il est en focus */
            transform: rotate(5deg); /* Rotation lorsqu'il est en focus */
        }

        button[type="submit"] {
            background-color: #9C27B0; /* Couleur de fond des boutons */
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Animation de transition */
            width: 100%;
            display: inline-block;
        }

        button[type="submit"]:hover {
            background-color: #7B1FA2; /* Couleur de fond des boutons lorsqu'ils sont survolés */
            transform: scale(1.05); /* Agrandissement lorsqu'ils sont survolés */
        }
        .back-link {
    color: #fff; /* Couleur du texte */
    background-color: #007BFF; /* Couleur de fond */
    padding: 10px 20px; /* Espacement du texte à l'intérieur du lien */
    border-radius: 25px; /* Bord arrondi */
    text-decoration: none; /* Suppression du soulignement par défaut */
}

.back-link:hover {
    background-color: #0056b3; /* Changement de couleur au survol */
}
    </style>
</head>
<body>
    <header>
        <h1>Account Settings</h1> <!-- Modification du titre en anglais -->
    </header>
    <main>
    <div style="position: absolute; top: 20px; right: 20px;">
    <a href="index.html" class="back-link">Back</a>
    </div>
        <form id="account-settings-form" action="account-settings.php" method="POST">
            <label for="nom">Name :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($medecin['Nom'] ?? ''); ?>" required>
            
            <label for="prenom">Surname :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($medecin['Prenom'] ?? ''); ?>" required>
            
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($medecin['Email'] ?? ''); ?>" required>
            
            <label for="password">ID :</label>
            <input type="password" id="password" name="password">
            
            <label for="specialite">Specialitie :</label>
            <input type="text" id="specialite" name="specialite" value="<?php echo htmlspecialchars($medecin['Specialite'] ?? ''); ?>">
            
            <button type="submit">Save Changes</button> <!-- Modification du texte du bouton en anglais -->
        </form>
    </main>
    <footer>
      
    </footer>
</body>
</html>




