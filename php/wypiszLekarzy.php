<?php
// Połączenie z bazą danych
$servername = "localhost:4306";
$username = "root";
$password = "";
$dbname = "baza_przychodnia";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Nieudane połączenie: " . $conn->connect_error);
}

// Wykonanie zapytania SELECT i przypisanie wyników do zmiennej $result
$sql = "SELECT ID_lekarza, Imie, Nazwisko FROM lekarze";
$result = $conn->query($sql);

// Wyświetlenie wyników na stronie
if ($result->num_rows > 0) {
    echo "<table><tr><th>ID lekarza</th><th>Imię</th><th>Nazwisko</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_lekarza"] . "</td><td>" . $row["Imie"] . "</td><td>" . $row["Nazwisko"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Brak danych";
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
