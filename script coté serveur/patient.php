
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
    <style>
        body {
            background-image: url('banniere-medicale-medecin-tenant-stethoscope.jpg');
            background-size: cover;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .table-container {
            display: inline-block;
            margin: 20px;
            border-radius: 10px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .patient-details-title {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 28px;
            color: #5c5c5c;
            font-family: 'Arial', sans-serif;
            text-transform: uppercase;
            animation: fadeIn 1s ease-in-out;
        }

        .assessment-form {
    max-width: 400px; /* Définissez la largeur maximale souhaitée */
    margin: auto; /* Centrez le formulaire horizontalement */
    margin-top: 2px;
    padding: 2px;
    border: 2px solid #007bff;
    border-radius: 20px;
    background-color: #f0f0f0;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
}


        .assessment-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .assessment-form select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        .assessment-form button {
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .assessment-form button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #6c757d;
            border: none;
            border-radius: 50px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
        }

        .button:active {
            transform: translateY(0);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .patient-details-strong {
            color: #2e86c1;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
            font-family: Arial, sans-serif;
            animation: fadeInTable 1s ease-in-out;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }

        @keyframes fadeInTable {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .styled-table th {
            background-color: #f2f2f2;
            border: 1px solid transparent;
            padding: 8px;
            text-align: left;
            color: #000;
        }

        .styled-table td {
            border: 1px solid transparent;
            padding: 8px;
            color: #000;
        }

        .styled-table {
            width: 40%;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid #ddd;
            font-family: Arial, sans-serif;
            animation: fadeInTable 1s ease-in-out;
        }

        .styled-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .patient-name {
            text-decoration: underline;
        }

        .etoile {
            cursor: pointer;
            font-size: 24px;
        }

        .etoile:hover,
        .etoile.selected {
            color: yellow;
        }

        .description {
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1 class="consultation-of-diagnostic-histories" style="color: #4CAF50; font-weight: bold; font-family: 'Courier New', Courier, monospace; font-size: 30px;">
    Consultation of Diagnostic Histories
</h1>

   
        <img src="touch-screen.gif" alt="GIF" style="vertical-align: middle; margin-left: 10px; width: 50px; height: 50px; border-radius: 50%;">
    </h1>
    <div style="position: absolute; top: 100px; right: 20px;">
    <a href="https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn?frameUrl=%2Fconsole%2Fsms%2Fwhatsapp%2Flearn%3Fx-target-region%3Dus1" class="button" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none;" target="_blank">Send WhatsApp Message</a>
</div>

<?php
if (isset($_GET['name'])) {
    $searchTerm = $_GET['name'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "telemedecine_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
 // Suppression des données de plus de 5 minutes
$sql_delete_old = "DELETE FROM save_assessments WHERE assessment_date < NOW() - INTERVAL 15 DAY";
$conn->query($sql_delete_old);

  
$conn->query($sql_delete_old);
    $sql = "SELECT ID, Nom, Prenom, Email FROM Patients WHERE Nom = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<table class='styled-table'>";
        while ($row = $result->fetch_assoc()) {

            echo "<tr><td colspan='2'>";
            echo "<form method='post' action='save_health_assessment.php' class='assessment-form'>";
            echo "<input type='hidden' name='patient_id' value='" . $row['ID'] . "'>";
            echo "<label for='assessment'>Health Assessment:</label>";
            echo "<div id='evaluation'>";
            for ($i = 1; $i <= 5; $i++) {
                echo "<span class='etoile' data-value='$i' title='" . getDescription($i) . "'>&#9733;</span>";
            }
            echo "</div>";
            echo "<select name='assessment' id='assessment'>";
            echo "<option value='1'>Normal</option>";
            echo "<option value='2'>Mild</option>";
            echo "<option value='3'>Moderate</option>";
            echo "<option value='4'>Severe</option>";
            echo "<option value='5'>Very Severe</option>";
            echo "</select>";
            echo "<button type='submit'>Save Assessment</button>";
            echo "</form>";
            echo "</td></tr>";

            $selectedCategory = isset($_POST['category']) ? $_POST['category'] : null;

            if ($selectedCategory) {
                $sql_health = "SELECT assessment_value, assessment_date FROM Health_Assessments WHERE patient_id = ? AND assessment_value = ? ORDER BY assessment_date";
                $stmt_health = $conn->prepare($sql_health);
                $stmt_health->bind_param("ii", $row['ID'], $selectedCategory);
            } else {
                $sql_health = "SELECT assessment_value, assessment_date FROM Health_Assessments WHERE patient_id = ? ORDER BY assessment_date";
                $stmt_health = $conn->prepare($sql_health);
                $stmt_health->bind_param("i", $row['ID']);
            }
            $stmt_health->execute();
            $result_health = $stmt_health->get_result();

            if ($result_health && $result_health->num_rows > 0) {
                $assessments = [];
                $dates = [];
                while ($row_health = $result_health->fetch_assoc()) {
                    $assessments[] = $row_health["assessment_value"];
                    $dates[] = $row_health["assessment_date"];
                }
                echo "<tr><td colspan='2'><h2>Health Assessments</h2></td></tr>";
                echo "<table class='styled-table'>";
                echo "<tr><th>Assessment Value</th><th>Assessment Date</th></tr>";
                foreach ($assessments as $index => $value) {
                    echo "<tr>";
                    echo "<td>" . $value . "</td>";
                    echo "<td>" . $dates[$index] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                // Regrouping functionality
                echo "<tr><td colspan='2'>";
                echo "<form method='post' action='' class='assessment-form'>";
                echo "<label for='category'>Group Assessments By Category:</label>";
                echo "<select name='category' id='category'>";
                echo "<option value='1'>Normal</option>";
                echo "<option value='2'>Mild</option>";
                echo "<option value='3'>Moderate</option>";
                echo "<option value='4'>Severe</option>";
                echo "<option value='5'>Very Severe</option>";
                echo "</select>";
                echo "<button type='submit'>Filter</button>";
                echo "</form>";
                echo "</td></tr>";
            } else {
                echo "No health assessments found for this patient.";
            }

            $stmt_health->close();
        }
        echo "</table>";

        if ($result->num_rows > 0) {
            ?>
           
            <?php
        }
    } else {
        echo "<p>No health assessments found for this patient $searchTerm.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Please provide a name to perform the search.</p>";
}

function getDescription($value) {
    switch ($value) {
        case 1:
            return "Normal";
        case 2:
            return "Mild";
        case 3:
            return "Moderate";
        case 4:
            return "Severe";
        case 5:
            return "Very Severe";
        default:
            return "";
    }
}
?>

    <div style="position: absolute; top: 60px; right: 20px;">
        <a href="consultation.php" class="button" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none;">Back </a>
    </div>

    <!-- Ajouter un conteneur pour les graphiques -->
    <div style="width: 40%; margin: 50px auto;">
        <canvas id="assessmentChart"></canvas>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var etoiles = document.querySelectorAll('.etoile');
            var selectAssessment = document.getElementById('assessment');

            etoiles.forEach(function(etoile) {
                etoile.addEventListener('mouseenter', function() {
                    var valeur = parseInt(etoile.getAttribute('data-value'));
                    var description = getDescription(valeur);
                    var descriptionElement = document.createElement('div');
                    descriptionElement.classList.add('description');
                    descriptionElement.textContent = description;
                    etoile.appendChild(descriptionElement);
                });

                etoile.addEventListener('mouseleave', function() {
                    var descriptionElement = etoile.querySelector('.description');
                    if (descriptionElement) {
                        etoile.removeChild(descriptionElement);
                    }
                });

                etoile.addEventListener('click', function() {
                    var valeur = parseInt(etoile.getAttribute('data-value'));
                    resetEtoiles();
                    for (var i = 0; i < valeur; i++) {
                        etoiles[i].classList.add('selected');
                    }
                    selectAssessment.value = valeur;
                });
            });

            selectAssessment.addEventListener('change', function() {
                var valeur = parseInt(selectAssessment.value);
                resetEtoiles();
                for (var i = 0; i < valeur; i++) {
                    etoiles[i].classList.add('selected');
                }
            });

            function resetEtoiles() {
                etoiles.forEach(function(etoile) {
                    etoile.classList.remove('selected');
                });
            }

            function getDescription(value) {
                switch (value) {
                    case 1:
                        return "Normal";
                    case 2:
                        return "Mild";
                    case 3:
                        return "Moderate";
                    case 4:
                        return "Severe";
                    case 5:
                        return "Very Severe";
                    default:
                        return "";
                }
            }

            // Charger les données du graphique si elles existent
            var assessments = <?php echo json_encode($assessments); ?>;
            var dates = <?php echo json_encode($dates); ?>;
            if (assessments && dates) {
                var ctx = document.getElementById('assessmentChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{
                            label: 'Health Assessments Over Time',
                            data: assessments,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        switch (value) {
                                            case 1:
                                                return 'Normal';
                                            case 2:
                                                return 'Mild';
                                            case 3:
                                                return 'Moderate';
                                            case 4:
                                                return 'Severe';
                                            case 5:
                                                return 'Very Severe';
                                            default:
                                                return value;
                                        }
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: {
                                top: 20,
                                left: 20,
                                right: 20,
                                bottom: 20
                            }
                        },
                        backgroundColor: 'white', // Set the background color of the chart to white
                    }
                });
            }
        });
    </script>
</body>
</html> 