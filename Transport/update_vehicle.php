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
    <?php
        include_once 'db_config.php';

        $id = "";
        $registrationNumber = "";
        $vehicleType = "";
        $capacity = "";
        $technicalState = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $id = $_POST['id'];
            
            // Pobieranie danych pojazdu z bazy danych
            $sql = "SELECT registration_number, vehicle_type, capacity, technical_state FROM Vehicles WHERE id = '$id'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $registrationNumber = $row['registration_number'];
                $vehicleType = $row['vehicle_type'];
                $capacity = $row['capacity'];
                $technicalState = $row['technical_state'];
            } else {
                echo "Nie znaleziono pojazdu o podanym ID.";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrationNumber']) && isset($_POST['vehicleType']) && isset($_POST['capacity']) && isset($_POST['technicalState'])) {
            $registrationNumber = $_POST['registrationNumber'];
            $vehicleType = $_POST['vehicleType'];
            $capacity = $_POST['capacity'];
            $technicalState = $_POST['technicalState'];

            if (empty($id)) {
                // Add new vehicle
                $query = "INSERT INTO vehicles (registration_number, vehicle_type, capacity, technical_state) 
                          VALUES ('$registrationNumber', '$vehicleType', '$capacity', '$technicalState')";
            } else {
                // Update existing vehicle
                $query = "UPDATE vehicles SET registration_number='$registrationNumber', vehicle_type='$vehicleType', capacity='$capacity', technical_state='$technicalState' WHERE id=$id";
            }

            if (mysqli_query($conn, $query)) {
                header('Location: main.php');
            } else {
                echo "Błąd: " . mysqli_error($conn);
            }
        }
    ?>

    <form action="update_vehicle.php" method="POST">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        
        <label for="registrationNumber">Numer rejestracyjny:</label><br>
        <input type="text" id="registrationNumber" name="registrationNumber" value="<?php echo $registrationNumber; ?>" required><br>
        
        <label for="vehicleType">Typ pojazdu:</label><br>
        <input type="text" id="vehicleType" name="vehicleType" value="<?php echo $vehicleType; ?>" required><br>
        
        <label for="capacity">Pojemność:</label><br>
        <input type="text" id="capacity" name="capacity" value="<?php echo $capacity; ?>" required><br>
        
        <label for="technicalState">Stan techniczny:</label><br>
        <input type="text" id="technicalState" name="technicalState" value="<?php echo $technicalState; ?>" required><br>
        
        <button type="submit">Zapisz pojazd</button>
    </form>
    <button onclick="redirectToMain()" id="wroc">Powrót</button>

    <div id="loading">
        <img src="IMG/ekran_ladowania.gif" alt="Ładowanie...">
    </div>
</body>
</html>
