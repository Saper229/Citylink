<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="IMG/ikona.png" type="image/x-icon">
    <title>Edytuj Pojazd</title>
    <link rel="stylesheet" href="style_form.css">
</head>
<body>
<?php
    include_once 'db_config.php';

    $id = "";
    $line = "";
    $stop = "";
    $departure_time = "";
    $arrival_time = "";
    $day_of_week = "";
    $holiday = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Pobieranie danych rozkładu z bazy danych
        $sql = "SELECT line, stop, departure_time, arrival_time, day_of_week, holiday FROM Timetables WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $line = $row['line'];
            $stop = $row['stop'];
            $departure_time = $row['departure_time'];
            $arrival_time = $row['arrival_time'];
            $day_of_week = $row['day_of_week'];
            $holiday = $row['holiday'];
        } else {
            echo "Nie znaleziono rozkładu o podanym ID.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['line']) && isset($_POST['stop']) && isset($_POST['departure_time']) && isset($_POST['arrival_time']) && isset($_POST['day_of_week']) && isset($_POST['holiday'])) {
        $line = $_POST['line'];
        $stop = $_POST['stop'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];
        $day_of_week = $_POST['day_of_week'];
        $holiday = $_POST['holiday'];

        if (!empty($id)) {
            $query = "UPDATE Timetables SET line='$line', stop='$stop', departure_time='$departure_time', arrival_time='$arrival_time', day_of_week='$day_of_week', holiday='$holiday' WHERE id=$id";

            if (mysqli_query($conn, $query)) {
                header('Location: main.php');
            } else {
                echo "Błąd: " . mysqli_error($conn);
            }
        }
    }
?>

<form action="update_schedule.php" method="POST">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    
    <label for="line">Linia:</label><br>
    <input type="text" id="line" name="line" value="<?php echo $line; ?>" required><br>
    
    <label for="stop">Przystanek:</label><br>
    <input type="text" id="stop" name="stop" value="<?php echo $stop; ?>" required><br>
    
    <label for="departure_time">Godzina odjazdu:</label><br>
    <input type="text" id="departure_time" name="departure_time" value="<?php echo $departure_time; ?>" required><br>
    
    <label for="arrival_time">Godzina przyjazdu:</label><br>
    <input type="text" id="arrival_time" name="arrival_time" value="<?php echo $arrival_time; ?>" required><br>
    
    <label for="day_of_week">Dzień tygodnia:</label><br>
    <input type="text" id="day_of_week" name="day_of_week" value="<?php echo $day_of_week; ?>" required><br>
    
    <label for="holiday">Dzień świąteczny:</label><br>
    <input type="text" id="holiday" name="holiday" value="<?php echo $holiday; ?>" required><br>
    
    <button type="submit">Zapisz zmiany</button>
</form>

</body>
</html>
