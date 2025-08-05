<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients List</title>
    <style>
        /* Ajouter le fond d'image */
        body {
            background-image: url('khouloud.jpg'); /* Chemin de votre image */
            background-size: cover; /* Ajuste la taille de l'image pour couvrir toute la page */
            background-position: center; /* Centre l'image horizontalement et verticalement */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Fond blanc avec opacité */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
            font-size: 24px;
        }

        .back-link {
            display: block;
            width: fit-content;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #0056b3;
        }

        /* Style du formulaire */
        form {
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #666;
        }

        input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease; /* Ajout de transition pour la couleur */
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
            color: #fff; /* Changer la couleur du texte en blanc */
        }

        .patients-list {
            padding: 0;
            list-style: none;
        }

        .patient-item {
            background-color: rgba(249, 249, 249, 0.8); /* Fond légèrement transparent */
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .patient-item:hover {
            transform: translateY(-5px);
            background-color: rgba(240, 240, 240, 0.9);
        }

        .patient-item h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #007BFF;
            cursor: pointer;
            transition: transform 0.3s ease, font-size 0.3s ease; /* Transition de la taille de la police et de la transformation */
        }

        .patient-item h3:hover {
            transform: scale(1.1); /* Agrandissement au survol */
        }

        .patient-item p {
            margin: 0;
            line-height: 1.6;
            color: #666;
            display: none;
        }

        .patient-item:hover p {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Patients</h1>
        <form method="post">
            <label for="startLetter">Enter the starting letter:</label>
            <input type="text" id="startLetter" name="startLetter">
            <button type="submit">Filter</button>
        </form>
        <a href="index.html" class="back-link">Back</a>
        <ul class="patients-list">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "telemedecine_db";
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            // Vérifier la connexion
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Fetch patients from database
            $sql = "SELECT Nom, Prenom, DateNaissance, Sexe, Adresse, Telephone, Email FROM patients";
            
            // Check if the form is submitted and the start letter is provided
            if(isset($_POST['startLetter'])) {
                $startLetter = $_POST['startLetter'];
                // Modify the SQL query to filter by starting letter
                $sql .= " WHERE Nom LIKE '$startLetter%'";
            }
            
            $result = $conn->query($sql);
            
            // Display patients as list
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li class='patient-item'>";
                    echo "<h3 onclick='expandAndRedirect(\"" . $row["Nom"] . "\")'>" . $row["Nom"] . "</h3>";
                    echo "<p><strong>name:</strong> " . $row["Nom"] . "</p>";
                    echo "<p><strong>surname:</strong> " . $row["Prenom"] . "</p>";
                    echo "<p><strong>Date of Birth:</strong> " . $row["DateNaissance"] . "</p>";
                    echo "<p><strong>Sex:</strong> " . $row["Sexe"] . "</p>";
                    echo "<p><strong>Address:</strong> " . $row["Adresse"] . "</p>";
                    echo "<p><strong>Phone:</strong> " . $row["Telephone"] . "</p>";
                    echo "<p><strong>Email:</strong> " . $row["Email"] . "</p>";
                    echo "</li>";
                }
            } else {
                echo "0 results";
            }
            
            // Close database connection
            $conn->close();
            ?>
                    </ul>
                </div>
                <script>
                    function expandAndRedirect(patientName) {
                        // Agrandir le texte avant la redirection
                        const h3Elements = document.querySelectorAll('.patient-item h3');
                        h3Elements.forEach(h3 => {
                            if (h3.innerText.includes(patientName)) {
                                h3.style.transition = "all 0.5s ease";
                                h3.style.transform = "scale(1.5)";
                            }
                        });
            
                        // Rediriger après une courte pause
                        setTimeout(() => {
                            window.location.href = 'donnees_physiologiques.php?name=' + encodeURIComponent(patientName);
                        }, 500);
                    }
                </script>
            </body>
            </html>  