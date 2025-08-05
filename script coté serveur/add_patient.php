<?php
// Connexion à la base de données (remplacez les valeurs par vos propres informations de connexion)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dashboard_polysomnographe";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifiez si le formulaire a été soumis
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $dob = $_POST["dob"];
    // Ajoutez d'autres champs si nécessaire

    // Préparez et exécutez la requête SQL pour insérer les données dans la base de données
    $sql = "INSERT INTO nv_patients (first_name, last_name, dob) VALUES ('$first_name', '$last_name', '$dob')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New patient added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fermez la connexion à la base de données
$conn->close();
?>
