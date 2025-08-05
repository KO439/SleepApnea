<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Configuration</title>
    <style>
        /* Styles pour le lien */
        .back-link {
            display: inline-block; /* Convertit le lien en élément de bloc pour pouvoir appliquer des styles de largeur, hauteur, etc. */
            padding: 10px 20px; /* Ajoute de l'espace à l'intérieur du lien */
            text-decoration: none; /* Supprime le soulignement par défaut */
            color: white; /* Couleur du texte du lien */
            border: 2px solid blue; /* Ajoute une bordure bleue */
            border-radius: 5px; /* Arrondit les coins de la bordure */
            background-color: blue; /* Couleur de fond bleue */
        }
        .back-link:hover {
            background-color: white; /* Change la couleur de fond au survol */
            color: blue; /* Change la couleur du texte au survol */
        }

        /* Styles pour le tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* Ajoute de l'espace en bas du tableau */
            background-color: rgba(255, 255, 255, 0.8); /* Couleur de fond du tableau avec transparence */
        }
        th, td {
            border: 1px solid black;
            padding: 10px; /* Augmente la taille de la zone de remplissage */
            text-align: left;
            color: black; /* Couleur du texte */
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold; /* Rend le texte en gras */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Change la couleur de fond de chaque deuxième ligne */
        }
        tr:hover {
            background-color: #eaeaea; /* Change la couleur de fond au survol */
        }

        /* Ajouter le fond d'image */
        body {
            background-image: url('khouloud.jpg'); /* Chemin de votre image */
            background-size: cover; /* Ajuste la taille de l'image pour couvrir toute la page */
            background-position: center; /* Centre l'image horizontalement et verticalement */
        }

        /* Centrer le titre */
        h2 {
            text-align: center;
            color: white; /* Changer la couleur du texte du titre en blanc */
        }
        .button-container {
            text-align: center; /* Centre les éléments horizontalement */
            margin-top: 20px; /* Ajoute de la marge en haut */
            margin-bottom: 20px; /* Ajoute de la marge en bas */
        }

        .button {
            display: inline-block;
            padding: 15px 30px; /* Augmente la taille des boutons */
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 10px; /* Arrondit les coins */
            background-color: #6495ED; /* Bleu acier */
            background-image: linear-gradient(45deg, #6495ED, #ADD8E6); /* Dégradé de bleu acier à bleu pâle */
            font-size: 16px; /* Ajuste la taille de la police */
            cursor: pointer; /* Change le curseur au survol */
            margin: 10px; /* Ajoute de la marge autour des boutons */
            transition: all 0.3s ease; /* Transition douce pour l'animation */
        }

        .button:hover {
            background-image: linear-gradient(45deg, #ADD8E6, #6495ED); /* Inversion du dégradé */
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75); /* Ombre au survol */
            transform: scale(1.1); /* Légère augmentation de taille au survol */
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div style="position: absolute; top: 20px; right: 20px;">
        <a href="index.html" class="button back-link">Back </a>
    </div>
    <h2>Medical Parameters Setup</h2>
</h2>
    

    

    <!-- Barre de recherche -->
    <input type="text" id="searchInput" class="search-bar" placeholder="Search parameters..." onkeyup="filterTable()">
    
    <table id="configTable">
        <tr>
            <th>ID</th>
            <th>Parameter</th>
            <th>Description</th>
            <th>Measurement Unit</th>
            <th>Min Threshold</th>
            <th>Max Threshold</th>
        </tr>
        <?php
            // Connexion à la base de données
            $mysqli = new mysqli("localhost", "root", "", "telemedecine_db");

            // Vérification de la connexion
            if ($mysqli->connect_error) {
                die("La connexion a échoué : " . $mysqli->connect_error);
            }

            // Initialisation de la variable de requête
            $sql = "SELECT * FROM Configuration_Systeme";

            // Vérification si la barre de recherche est remplie
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql .= " WHERE Parametre LIKE '$search%'"; // Recherche les paramètres commençant par la lettre saisie
            }

            // Exécution de la requête
            $result = $mysqli->query($sql);

            // Affichage des données dans le tableau
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID"] . "</td>";
                    echo "<td>" . $row["Parametre"] . "</td>";
                    echo "<td>" . $row["Description"] . "</td>";
                    echo "<td>" . $row["UniteMesure"] . "</td>";
                    echo "<td>" . $row["SeuilMin"] . "</td>";
                    echo "<td>" . $row["SeuilMax"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No results</td></tr>";
            }

            // Fermeture de la connexion
            $mysqli->close();
        ?>
    </table>
    
    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("configTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // 1 corresponds to the Parameter column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
    
</body>
</html>
