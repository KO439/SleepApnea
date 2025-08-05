<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telemedecine_db"; // Remplacer par le nom de votre base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);
// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_delete_old = "DELETE FROM donnees_sante WHERE timestamp < NOW() - INTERVAL 56000 Minute";
$conn->query($sql_delete_old);

// Récupération de la date et de l'heure actuelles
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
$time = date("H:i:s");

// Vérification si les données sont reçues via GET
if (!empty($_GET['spo2']) && !empty($_GET['heartRate']) && !empty($_GET['amplifiedECG'])) {
    $spo2 = $_GET['spo2'];
    $heart_rate = $_GET['heartRate'];
    $ecg = $_GET['amplifiedECG'];

    // Calculer l'état en fonction des valeurs de SPO2
    $etat = "Normal"; // Par défaut
    if ($spo2 >= 50 && $spo2 <= 93) {
        $etat = "Hypoxemia";
    } elseif ($spo2 >= 0 && $spo2 < 50) {
        $etat = "Hypopnea";
    } elseif ($spo2 >= 99 && $spo2 <= 100) {
        $etat = "Hyperoxia";
    }

    // Requête SQL pour insérer les données dans la table
    $sql = "INSERT INTO donnees_sante (spo2, heart_rate, ecg, timestamp)
            VALUES (?, ?, ?, CURRENT_TIMESTAMP)";

    // Préparation de la requête
    $stmt = $conn->prepare($sql);
    // Liaison des paramètres
    $stmt->bind_param("iid", $spo2, $heart_rate, $ecg);

    // Exécution de la requête
    if ($stmt->execute()) {
        echo "OK";

        // Envoyer une notification
        $apiKey = 'CU3VJLB1E6TRGQZK285HZK4RC'; // Votre clé API Notify My Device
        $applicationName = 'HealthMonitor'; // Nom de votre application
        $message = " patient fatma Zahra:\n";
   
        $message .= "SPO2: $spo2%\n";
        $message .= "Heart Rate: $heart_rate BPM\n";
        $message .= "ECG: $ecg V\n";
        $message .= "State: $etat\n";

        $url = 'https://www.notifymydevice.com/push';
        $data = array(
            'ApiKey' => $apiKey,
            'PushTitle' => 'New Health Data Alert',
            'PushText' => $message,
            'PushLink' => '',
            'PushName' => $applicationName
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            echo "Notification Error";
        }
    } else {
        echo "Erreur : " . $stmt->error;
    }

    // Fermeture de la connexion
    $stmt->close();
}

// Requête SQL pour récupérer les données
$sql = "SELECT id, spo2, heart_rate, ecg, timestamp FROM donnees_sante";

// Si un état est sélectionné, ajoutez une clause WHERE pour filtrer par état
if (isset($_GET['etat']) && !empty($_GET['etat'])) {
    $etat_filter = $_GET['etat'];
    $sql .= " WHERE ";
    if ($etat_filter === 'Hypoxemia') {
        $sql .= "spo2 BETWEEN 50 AND 93";
    } elseif ($etat_filter === 'Hypopnea') {
        $sql .= "spo2 BETWEEN 0 AND 50";
    } elseif ($etat_filter === 'Hyperoxia') {
        $sql .= "spo2 BETWEEN 99 AND 100";
    } elseif ($etat_filter === 'Normal') {
        $sql .= "spo2 > 93 AND spo2 < 99";
    }
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

// Vérifiez si la requête a réussi
if (!$result) {
    die("Erreur de requête : " . $conn->error);
}

// Requête SQL pour récupérer les données de SPO2
$sql_spo2 = "SELECT spo2, timestamp FROM donnees_sante ORDER BY id DESC";
$result_spo2 = $conn->query($sql_spo2);

// Vérifiez si la requête a réussi
if (!$result_spo2) {
    die("Erreur de requête : " . $conn->error);
}

// Initialisation des tableaux pour stocker les données de SPO2 et les timestamps
$spo2_data = [];
$spo2_timestamps = [];

if ($result_spo2->num_rows > 0) {
    while ($row_spo2 = $result_spo2->fetch_assoc()) {
        // Stocker les données de SPO2 et les timestamps dans des tableaux
        $spo2_data[] = $row_spo2["spo2"];
        $spo2_timestamps[] = $row_spo2["timestamp"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique ECG et SPO2</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('stethoscope-2617700_1280.jpg');
            background-size: cover;
        }

        #c4ytable {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 40%; /* Réduire la largeur du tableau */
            margin: auto; /* Centrer le tableau */
            background-color: rgba(255, 255, 255, 0.5); /* Couleur de fond avec une opacité */
        }

        #c4ytable td, #c4ytable th {
            border: 1px solid rgba(0, 0, 0, 0.5); /* Bordures avec une opacité */
            padding: 8px; /* Réduire l'espacement intérieur */
            font-size: 12px; /* Réduire la taille de la police */
        }

        #c4ytable tr:nth-child(even) {
            background-color: rgba(240, 240, 240, 0.5); /* Couleur de fond des lignes paires avec une opacité */
        }

        #c4ytable tr:hover {
            background-color: rgba(221, 221, 221, 0.5); /* Couleur de fond au survol avec une opacité */
        }

        #c4ytable th {
            padding-top: 12px; /* Réduire l'espacement de la cellule d'en-tête */
            padding-bottom: 12px; /* Réduire l'espacement de la cellule d'en-tête */
            text-align: left;
            background-color: rgba(0, 123, 255, 0.8); /* Couleur de fond de l'en-tête avec une opacité */
            color: white;
        }

        #c4ytable th:hover {
            background-color: rgba(0, 86, 179, 0.8); /* Couleur de fond de l'en-tête au survol avec une opacité */
        }

        #ecgChartContainer, #spo2ChartContainer {
            max-width: 600px;
            margin: auto;
        }

        #navigation {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style pour le lien dynamique */
        #navigation a {
            text-decoration: none;
            padding: 15px 30px;
            background-color: rgba(0, 123, 255, 0.8);
            color: white;
            border-radius: 10px;
            margin: 5px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.3s, background-color 0.3s;
        }

        #navigation a:hover {
            background-color: rgba(0, 86, 179, 0.8);
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<div id="navigation">
    <a href="consultation.php">BACK</a>
    <a href="export_csv.php">Export CSV</a>
    <a href="export_excel.php">Export Excel</a>
    <a href="patient.php?name=fatma">Alerts archive</a>
</div>

<div id="ecgChartContainer">
    <canvas id="ecgChart" width="400" height="200"></canvas>
</div>

<div id="spo2ChartContainer">
    <canvas id="spo2Chart" width="400" height="200"></canvas>
</div>

<div>
    <form method="GET" action="">
        <label for="etat">Filter by state :</label>
        <select name="etat" id="etat">
            <option value="Normal">Normal</option>
            <option value="Hypoxemia">Hypoxemia</option>
            <option value="Hypopnea">Hypopnea</option>
            <option value="Hyperoxia">Hyperoxia</option>
        </select>
        <input type="submit" value="Filter">
    </form>
</div>

<div id="cards" class="cards">
    <table id='c4ytable'>
        <tr>
            <th>Sr.No.</th>
            <th>SPO2</th>
            <th>Heart Rate</th>
            <th>ECG</th>
            <th>State</th>
            <th>Timestamp</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Calcul de l'état en fonction de la valeur de SpO2
                $etat = "Normal"; // Par défaut
                if ($row["spo2"] >= 50 && $row["spo2"] <= 93) {
                    $etat = "Hypoxemia";
                } elseif ($row["spo2"] >= 0 && $row["spo2"] < 50) {
                    $etat = "Hypopnea";
                } elseif ($row["spo2"] >= 99 && $row["spo2"] <= 100) {
                    $etat = "Hyperoxia";
                }

                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["spo2"] . "</td>";
                echo "<td>" . $row["heart_rate"] . "</td>";
                echo "<td>" . $row["ecg"] . "</td>";
                echo "<td>" . $etat . "</td>";
                echo "<td>" . $row["timestamp"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data found</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Requête PHP pour récupérer les données d'ECG -->
<?php
$sql_ecg = "SELECT ecg, timestamp FROM donnees_sante ORDER BY id DESC";
$result_ecg = $conn->query($sql_ecg);

// Vérifiez si la requête a réussi
if (!$result_ecg) {
    die("Erreur de requête : " . $conn->error);
}

// Initialisation des tableaux pour stocker les données d'ECG et les timestamps
$ecg_data = [];
$timestamps = [];

if ($result_ecg->num_rows > 0) {
    while ($row_ecg = $result_ecg->fetch_assoc()) {
        // Stocker les données d'ECG et les timestamps dans des tableaux
        $ecg_data[] = $row_ecg["ecg"];
        $timestamps[] = $row_ecg["timestamp"];
    }
}
?>

<script>
    // Récupérer les données PHP dans JavaScript
    var ecgData = <?php echo json_encode($ecg_data); ?>;
    var timestamps = <?php echo json_encode($timestamps); ?>;

    // Créer un tableau d'objets pour les labels de l'axe X
    var timeLabels = timestamps.map(function(timestamp) {
        return new Date(timestamp).toLocaleTimeString();
    });

    // Créer un graphique avec Chart.js pour l'ECG
    var ctx = document.getElementById('ecgChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: timeLabels.reverse(), // Inverser les labels pour les afficher dans l'ordre chronologique
            datasets: [{
                label: 'ECG',
                data: ecgData.reverse(), // Inverser les données pour les afficher dans l'ordre chronologique
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });

    // Récupérer les données de SPO2 PHP dans JavaScript
    var spo2Data = <?php echo json_encode($spo2_data); ?>;
    var spo2Timestamps = <?php echo json_encode($spo2_timestamps); ?>;

    // Créer un tableau d'objets pour les labels de l'axe X pour SPO2
    var spo2TimeLabels = spo2Timestamps.map(function(timestamp) {
        return new Date(timestamp).toLocaleTimeString();
    });

    // Créer un graphique avec Chart.js pour SPO2
    var spo2Ctx = document.getElementById('spo2Chart').getContext('2d');
    var spo2Chart = new Chart(spo2Ctx, {
        type: 'line',
        data: {
            labels: spo2TimeLabels.reverse(), // Inverser les labels pour les afficher dans l'ordre chronologique
            datasets: [{
                label: 'SPO2',
                data: spo2Data.reverse(), // Inverser les données pour les afficher dans l'ordre chronologique
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Commencer à l'axe y à zéro
                }
            }
        }
    });
</script>

</body>
</html>

<?php
// Fermeture de la connexion
$conn->close();
?>
