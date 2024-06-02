<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Planowanie Harmonogramów</title>
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
    <h2>Planowanie Harmonogramów</h2>
    <form action="create_schedule.php" method="POST">
        <label for="line">Linia:</label><br>
        <input type="text" id="line" name="line" required><br>

        <label for="stop">Przystanek:</label><br>
        <input type="text" id="stop" name="stop" required><br>

        <label for="departure_time">Godzina odjazdu:</label><br>
        <input type="time" id="departure_time" name="departure_time" required><br>

        <label for="arrival_time">Godzina przyjazdu:</label><br>
        <input type="time" id="arrival_time" name="arrival_time" required><br>

        <label for="day_of_week">Dzień tygodnia:</label><br>
        <select id="day_of_week" name="day_of_week" required>
            <option value="poniedziałek">Poniedziałek</option>
            <option value="wtorek">Wtorek</option>
            <option value="środa">Środa</option>
            <option value="czwartek">Czwartek</option>
            <option value="piątek">Piątek</option>
            <option value="sobota">Sobota</option>
            <option value="niedziela">Niedziela</option>
        </select><br>

        <label for="holiday">Dzień świąteczny:</label><br>
        <select id="holiday" name="holiday" required>
            <option value="Tak">Tak</option>
            <option value="Nie">Nie</option>
        </select><br>

        <button type="submit">Zapisz harmonogram</button>
    </form>
    <button onclick="redirectToMain()" id="wroc">Powrót</button>

<div id="loading">
    <img src="IMG/ekran_ladowania.gif" alt="Ładowanie...">
</div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db_config.php';

        $line = $conn->real_escape_string($_POST['line']);
        $stop = $conn->real_escape_string($_POST['stop']);
        $departure_time = $conn->real_escape_string($_POST['departure_time']);
        $arrival_time = $conn->real_escape_string($_POST['arrival_time']);
        $day_of_week = $conn->real_escape_string($_POST['day_of_week']);
        $holiday = $conn->real_escape_string($_POST['holiday']);

        $sql = "INSERT INTO timetables (line, stop, departure_time, arrival_time, day_of_week, holiday)
                VALUES ('$line', '$stop', '$departure_time', '$arrival_time', '$day_of_week', '$holiday')";

        if ($conn->query($sql) === TRUE) {
            echo "Nowy harmonogram został pomyślnie zapisany";
        } else {
            echo "Błąd: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
