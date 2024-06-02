
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja Pasażerów</title>
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
    <h2>Rejestracja pasażerów</h2>
    <form action="register_passenger.php" method="POST">
        <label for="name">Imie i Nazwisko:</label><br/>
        <input type="text" id="name" name="name" required><br>
        
        <label for="phone">Numer telefonu:</label><br/>
        <input type="tel" id="phone" name="phone" required><br>
        
        <label for="email">Adres e-mail:</label><br/>
        <input type="email" id="email" name="email" required><br>
        
        <label for="preferences">Preferencje dotyczące podróży:</label><br/>
        <input type="text" id="preferences" name="preferences" required><br>
        
        <button type="submit">Zarejestruj pasażera</button>
    </form>
    <button onclick="redirectToMain()" id="wroc">Powrót</button>

<div id="loading">
    <img src="IMG/ekran_ladowania.gif" alt="Ładowanie...">
</div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db_config.php';

        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $preferences = $_POST['preferences'];

        $sql = "INSERT INTO Passengers (name, phone, email, preferences)
                VALUES ('$name', '$phone', '$email', '$preferences')";

        if ($conn->query($sql) === TRUE) {
            echo "Nowy pasażer został pomyślnie zarejestrowany";
        } else {
            echo "Błąd: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
