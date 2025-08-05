<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Patient</title>
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4; /* Couleur de fond */
    background-image: url('banniere-medicale-medecin-tenant-stethoscope.jpg'); /* Image de fond */
    background-size: cover; /* Redimensionne l'image pour remplir l'ensemble de la page */
    color: #333;
}

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
            color: #007BFF;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: none;
        }

        .form-group {
            position: relative;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #007BFF;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: calc(100% - 20px);
            padding: 10px;
            border-radius: 25px;
            border: none;
            background-color: #f2f2f2;
            transition: background-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        select:focus {
            background-color: #ddd;
        }

        button {
            background: linear-gradient(45deg, #007BFF, #00FFFF);
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            width: 100%;
            display: block;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
        }

        button:hover {
            background: linear-gradient(45deg, #0056b3, #00cccc);
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    <div class="container">
        <h1 class="title">Add a New Patient</h1>
        <div style="position: absolute; top: 20px; right: 20px;">
        <div style="position: absolute; top: 20px; right: 20px;">
    <a href="index.html" class="back-link">Back</a>
</div>

    </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table>
                <tr>
                    <td><label for="nom">Name:</label></td>
                    <td class="form-group"><input type="text" id="nom" name="nom" required></td>
                </tr>
                <tr>
                    <td><label for="prenom">First Name:</label></td>
                    <td class="form-group"><input type="text" id="prenom" name="prenom" required></td>
                </tr>
                <tr>
                    <td><label for="date_naissance">Date of Birth:</label></td>
                    <td class="form-group"><input type="date" id="date_naissance" name="date_naissance"></td>
                </tr>
                <tr>
                    <td><label for="sexe">Gender:</label></td>
                    <td class="form-group">
                        <select id="sexe" name="sexe">
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="adresse">Address:</label></td>
                    <td class="form-group"><input type="text" id="adresse" name="adresse"></td>
                </tr>
                <tr>
                    <td><label for="telephone">Phone:</label></td>
                    <td class="form-group"><input type="text" id="telephone" name="telephone"></td>
                </tr>
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td class="form-group"><input type="email" id="email" name="email"></td>
                </tr>
            </table>
            <button type="submit">Add Patient</button>
        </form>
    </div>

    <?php
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "telemedecine_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérifier si le formulaire est soumis
    if ($_SERVER && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
        $sexe = $_POST['sexe'];
        $adresse = $_POST['adresse'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];

        // Préparer et exécuter la requête SQL pour insérer les données dans la table Patients
        $sql = "INSERT INTO Patients (Nom, Prenom, DateNaissance, Sexe, Adresse, Telephone, Email)
                VALUES ('$nom', '$prenom', '$date_naissance', '$sexe', '$adresse', '$telephone', '$email')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('The patient has been successfully added.');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Fermer la connexion à la base de données
    $conn->close();
    ?>
</body>
</html>