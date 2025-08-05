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

// Définir les en-têtes pour le téléchargement du fichier Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="data.xls"');
header('Cache-Control: max-age=0');

// Commencer la table HTML pour les données Excel
echo "<table border='1'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>SPO2</th>";
echo "<th>Heart Rate</th>";
echo "<th>ECG</th>";
echo "<th>Timestamp</th>";
echo "</tr>";

// Requête SQL pour sélectionner les données
$sql = "SELECT id, spo2, heart_rate, ecg, timestamp FROM donnees_sante";
$result = $conn->query($sql);

// Insérer les lignes de données dans la table HTML
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['spo2'] . "</td>";
        echo "<td>" . $row['heart_rate'] . "</td>";
        echo "<td>" . $row['ecg'] . "</td>";
        echo "<td>" . $row['timestamp'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No data found</td></tr>";
}

// Fin de la table HTML
echo "</table>";

// Fermer la connexion à la base de données
$conn->close();
?>
