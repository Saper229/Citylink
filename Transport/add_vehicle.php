
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodawanie Pojazdów</title>
    <link rel="stylesheet" href="style_form.css">
    <link rel="shortcut icon" href="IMG/ikona.png" type="image/x-icon">
    <style>
        #loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
    <script>
        function redirectToMain() {
            document.getElementById('loading').style.display = 'block';
            setTimeout(function() {
                window.location.href = 'main.php';
            }, 3000);
        }
    </script>
</head>
<body>
    <h2>Dodawanie, edytowanie i usuwanie pojazdów</h2>
    <form action="add_vehicle.php" method="POST">
        <label for="registrationNumber">Numer rejestracyjny:</label><br>
        <input type="text" id="registrationNumber" name="registrationNumber"><br>
        
        <label for="vehicleType">Typ pojazdu:</label><br>
        <input type="text" id="vehicleType" name="vehicleType"><br>
        
        <label for="capacity">Pojemność:</label><br>
        <input type="text" id="capacity" name="capacity"><br>
        
        <label for="technicalState">Stan techniczny:</label><br>
        <input type="text" id="technicalState" name="technicalState"><br>
        
        <button type="submit">Zapisz pojazd</button>
    </form>
    <button onclick="redirectToMain()" id="wroc">Powrót</button>

<div id="loading">
    <img src="IMG/ekran_ladowania.gif" alt="Ładowanie...">
</div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db_config.php';

        $registrationNumber = $_POST['registrationNumber'];
        $vehicleType = $_POST['vehicleType'];
        $capacity = $_POST['capacity'];
        $technicalState = $_POST['technicalState'];

        $sql = "INSERT INTO Vehicles (registration_number, vehicle_type, capacity, technical_state)
                VALUES ('$registrationNumber', '$vehicleType', '$capacity', '$technicalState')";

        if ($conn->query($sql) === TRUE) {
            echo "Nowy pojazd został pomyślnie dodany";
        } else {
            echo "Błąd: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
