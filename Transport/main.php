<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Citylink</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="IMG/ikona.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div id="main">
<h2>Harmonogramy</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Linia</th>
        <th>Przystanek</th>
        <th>Godzina odjazdu</th>
        <th>Godzina przyjazdu</th>
        <th>Dzień tygodnia</th>
        <th>Dzień świąteczny</th>
        <th>Edytuj</th>
        <th>Usuń</th>
    </tr>
    <?php
    include 'db_config.php';

    if (isset($_POST['delete'])) {
        $id_to_delete = $_POST['id'];
        
        $delete_sql = "DELETE FROM Timetables WHERE id='$id_to_delete'";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Rekord został pomyślnie usunięty.";
        } else {
            echo "Błąd podczas usuwania rekordu: " . $conn->error;
        }
    }

    $sql = "SELECT id, line, stop, departure_time, arrival_time, day_of_week, holiday FROM Timetables";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["line"] . "</td>";
            echo "<td>" . $row["stop"] . "</td>";
            echo "<td>" . $row["departure_time"] . "</td>";
            echo "<td>" . $row["arrival_time"] . "</td>";
            echo "<td>" . $row["day_of_week"] . "</td>";
            echo "<td>" . $row["holiday"] . "</td>";
            echo "<td><form method='POST' action='update_schedule.php'><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' value='Edytuj'></form></td>";
            echo "<td><form method='POST' action=''><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' name='delete' value='Usuń'></form></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>Brak danych</td></tr>";
    }

    $conn->close();
    ?>
</table>
    <h2>Pasażerowie</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Imie i Nazwisko</th>
        <th>Numer telefonu</th>
        <th>Adres e-mail</th>
        <th>Preferencje</th>
        <th>Edytuj</th>
        <th>Usuń</th>
    </tr>
    <?php
    include 'db_config.php';

    if (isset($_POST['delete'])) {
        $id_to_delete = $_POST['id'];
        $delete_sql = "DELETE FROM Passengers WHERE id='$id_to_delete'";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Rekord został pomyślnie usunięty.";
        } else {
            echo "Błąd podczas usuwania rekordu: " . $conn->error;
        }
    }

    $sql = "SELECT id, name, phone, email, preferences FROM Passengers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["preferences"] . "</td>";
            echo "<td><form method='POST' action='update_passenger.php'><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' value='Edytuj'></form></td>";
            echo "<td><form method='POST' action=''><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' name='delete' value='Usuń'></form></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Brak danych</td></tr>";
    }

    $conn->close();
    ?>
</table>

<h2>Pojazdy</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Numer rejestracyjny</th>
            <th>Typ pojazdu</th>
            <th>Pojemność</th>
            <th>Stan techniczny</th>
            <th>Edytuj</th>
            <th>Usuń</th>
        </tr>
        <?php
        include 'db_config.php';

        if (isset($_POST['delete'])) {
            $id_to_delete = $_POST['id'];
            $delete_sql = "DELETE FROM Vehicles WHERE id='$id_to_delete'";
            if ($conn->query($delete_sql) === TRUE) {
                echo "Rekord został pomyślnie usunięty.";
            } else {
                echo "Błąd podczas usuwania rekordu: " . $conn->error;
            }
        }

        $sql = "SELECT id, registration_number, vehicle_type, capacity, technical_state FROM Vehicles";
        $result = $conn->query($sql);

        $vehicles = array();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $vehicles[] = $row;
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["registration_number"] . "</td>";
                echo "<td>" . $row["vehicle_type"] . "</td>";
                echo "<td>" . $row["capacity"] . "</td>";
                echo "<td>" . $row["technical_state"] . "</td>";
                echo "<td><form method='POST' action='update_vehicle.php'><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' value='Edytuj'></form></td>";
                echo "<td><form method='POST' action=''><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' name='delete' value='Usuń'></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Brak danych</td></tr>";
        }

        $conn->close();

        // Sortowanie pojazdów według pojemności malejąco i wybieranie trzech największych
        usort($vehicles, function($a, $b) {
            return $b['capacity'] - $a['capacity'];
        });

        $top_vehicles = array_slice($vehicles, 0, 4);
        ?>
    </table>

    <!-- Dodaj miejsce na wykres -->
    <h2>Cztery największe pojemności (ilość miejsc) dla pojazdów</h2>
    <canvas id="capacityChart" width="400" height="200"></canvas>

    <!-- Skrypt do generowania wykresu -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var ctx = document.getElementById('capacityChart').getContext('2d');
            var capacityChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php
                        foreach ($top_vehicles as $vehicle) {
                            echo '"' . $vehicle['registration_number'] . '",';
                        }
                        ?>
                    ],
                    datasets: [{
                        label: 'Pojemność',
                        data: [
                            <?php
                            foreach ($top_vehicles as $vehicle) {
                                echo $vehicle['capacity'] . ',';
                            }
                            ?>
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <h2>Preferencje pasażerów</h2>
    <div id="preferencesChartContainer" style="width: 400px; height: 400px;">
    <canvas id="preferencesChart"></canvas>
</div>
<?php
include 'db_config.php';

// Pobierz preferencje użytkowników
$sql = "SELECT preferences, COUNT(*) AS preference_count FROM Passengers GROUP BY preferences ORDER BY preference_count DESC";
$result = $conn->query($sql);

$preferences_labels = array();
$preferences_counts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $preferences_labels[] = $row['preferences'];
        $preferences_counts[] = $row['preference_count'];
    }
}

$conn->close();
?>

<script>
    // Utwórz wykres kołowy
    var ctx = document.getElementById('preferencesChart').getContext('2d');
    var preferencesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($preferences_labels); ?>,
            datasets: [{
                label: 'Preferencje użytkowników',
                data: <?php echo json_encode($preferences_counts); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
    });
</script>
<?php
include 'db_config.php';

// Pobierz stan techniczny pojazdów
$sql = "SELECT technical_state, COUNT(*) AS state_count FROM Vehicles GROUP BY technical_state";
$result = $conn->query($sql);

$technical_state_labels = array();
$technical_state_counts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $technical_state_labels[] = $row['technical_state'];
        $technical_state_counts[] = $row['state_count'];
    }
}

$conn->close();
?>
<h2>Stan techniczny pojazdów</h2>
<div style="width: 400px; height: 400px;">
    <canvas id="technicalStatePieChart"></canvas>
</div>

<script>
    // Utwórz wykres kołowy
    var ctx = document.getElementById('technicalStatePieChart').getContext('2d');
    var technicalStatePieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($technical_state_labels); ?>,
            datasets: [{
                label: 'Stan techniczny pojazdów',
                data: <?php echo json_encode($technical_state_counts); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
    });
</script>





</div>
<div id="right">
    <h2>Formularze:</h2>
    <div id="linki">
        <a href="add_vehicle.php">Dodawanie pojazdów</a><br>
        <a href="create_schedule.php">Dodawanie rozkładów</a><br>
        <a href="register_passenger.php">Rejestracja pasażerów</a>
    </div>
    <a href="https://www.youtube.com/watch?v=aV4Yx7mvDw8" target="_blank"><img id="myImage" src="IMG/ad1.png" alt="Reklama"></a>

<script>
    // Tablica z nazwami plików obrazków
    var images = ['IMG/ad1.png', 'IMG/ad2.png', 'IMG/ad3.png','IMG/ad4.png','IMG/ad5.png'];
    var currentIndex = 0; // początkowy indeks obrazka

    // Funkcja zmieniająca obrazek co 3 sekundy
    function changeImage() {
        currentIndex = (currentIndex + 1) % images.length; // zmiana indeksu obrazka
        document.getElementById('myImage').src = images[currentIndex]; // zmiana źródła obrazka
    }
    setInterval(changeImage, 3000);
</script>
</div>
</body>
</html>
