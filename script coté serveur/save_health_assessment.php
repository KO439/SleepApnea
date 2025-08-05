<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Health Assessment</title>
    <style>
        /* Styles pour le message d'erreur et de succès */
        .message-container {
            margin-top: 20px;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .success-message {
            color: white; /* Blanc */
            font-size: 50px; /* Taille de police plus grande */
            font-weight: bold; /* Gras */
        }

        .error-message {
            color: black; /* Noir */
            font-size: 32px; /* Taille de police plus grande */
            font-weight: bold; /* Gras */
        }

        /* Styles pour le conteneur de boutons */
        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        /* Styles pour les boutons */
        .button {
            display: inline-block;
            padding: 15px 30px; /* Ajustement de la taille du bouton */
            font-size: 24px; /* Taille de police plus grande */
            font-weight: bold;
            color: black; /* Noir */
            background-color: #007bff; /* Bleu */
            border: none;
            border-radius: 8px; /* Coins arrondis */
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3; /* Bleu foncé */
        }

        /* Ajout de l'arrière-plan d'image */
        body {
            background-image: url('khouloud.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh; /* Ajuster la hauteur à la taille de l'écran */
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif; /* Définir une police de caractères par défaut */
        }
    </style>
</head>
<body>
<?php
// Vérifier si nous sommes dans un environnement web
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs requis existent
    if (isset($_POST['patient_id']) && isset($_POST['assessment'])) {
        // Récupérer les données du formulaire
        $patient_id = $_POST['patient_id'];
        $assessment = $_POST['assessment'];

        // Valider les données si nécessaire

        // Enregistrer l'évaluation de santé dans la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "telemedecine_db";

        // Créer une connexion à la base de données
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Préparer et exécuter la requête SQL pour insérer l'évaluation de santé
        $sql = "INSERT INTO Health_Assessments (patient_id, assessment_value) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        // Vérifier si la préparation de la requête a réussi
        if ($stmt === false) {
            echo "<div class='message-container'><p class='error-message'>Error preparing statement: " . htmlspecialchars($conn->error) . "</p></div>";
        } else {
            $stmt->bind_param("ii", $patient_id, $assessment);

            if ($stmt->execute()) {
                echo "<div class='message-container'><p class='success-message'>Health assessment saved successfully.</p></div>";
            } else {
                echo "<div class='message-container'><p class='error-message'>Error: " . htmlspecialchars($stmt->error) . "</p></div>";
            }

            // Fermer la requête préparée
            $stmt->close();
        }

        // Fermer la connexion à la base de données
        $conn->close();
    } else {
        echo "<div class='message-container'><p class='error-message'>Missing required fields.</p></div>";
    }
} else {
    echo "<div class='message-container'><p class='error-message'>Invalid request.</p></div>";
}
?>
<div class="button-container">
<a href="patient.php?name=fatma" class="button">Back</a>

</div>

</body>
</html>
