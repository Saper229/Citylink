<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj Pasażera</title>
    <link rel="stylesheet" href="style_form.css">
    <link rel="shortcut icon" href="IMG/ikona.png" type="image/x-icon">
</head>
<body>
    <h2>Edytuj pasażera</h2>
    <?php
    include 'db_config.php';

    // Sprawdzanie, czy istnieje ID pasażera w zapytaniu POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        $id = $_POST['id'];

        // Pobieranie danych pasażera z bazy danych
        $sql = "SELECT name, phone, email, preferences FROM Passengers WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $phone = $row['phone'];
            $email = $row['email'];
            $preferences = $row['preferences'];
        } else {
            echo "Nie znaleziono pasażera o podanym ID.";
        }
    }

    // Aktualizacja danych pasażera po edycji
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['preferences'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $preferences = $_POST['preferences'];

        if (!empty($id)) {
            $query = "UPDATE Passengers SET name='$name', phone='$phone', email='$email', preferences='$preferences' WHERE id=$id";

            if (mysqli_query($conn, $query)) {
                header('Location: main.php');
            } else {
                echo "Błąd: " . mysqli_error($conn);
            }
        }
    }
    ?>

    <form action="update_passenger.php" method="POST">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

        <label for="name">Imie i Nazwisko:</label><br/>
        <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required><br>
        
        <label for="phone">Numer telefonu:</label><br/>
        <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required><br>
        
        <label for="email">Adres e-mail:</label><br/>
        <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required><br>
        
        <label for="preferences">Preferencje dotyczące podróży:</label><br/>
        <input type="text" id="preferences" name="preferences" value="<?php echo isset($preferences) ? $preferences : ''; ?>" required><br>
        
        <button type="submit">Zapisz zmiany</button>
    </form>

    <button onclick="redirectToMain()" id="wroc">Powrót</button>
</body>
</html>
