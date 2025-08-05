<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telemedecine_db"; // Remplacer par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Définir les en-têtes pour le téléchargement du fichier CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// Création d'un flux de sortie pour le fichier CSV
$output = fopen('php://output', 'w');

// Insérer les en-têtes des colonnes dans le fichier CSV
fputcsv($output, array('ID', 'SPO2', 'Heart Rate', 'ECG', 'Timestamp'));

// Requête SQL pour sélectionner les données
$sql = "SELECT id, spo2, heart_rate, ecg, timestamp FROM donnees_sante";
$result = $conn->query($sql);

// Insérer les lignes de données dans le fichier CSV
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    echo "No data found";
}

// Fermer le flux de sortie
fclose($output);

// Fermer la connexion à la base de données
$conn->close();
?>
