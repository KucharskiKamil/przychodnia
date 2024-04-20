<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta'])) {
    $id_pacjenta = $_POST['id_pacjenta'];

    // Zapytanie SQL do pobrania danych pacjenta dla danego id_pacjenta
    $sql = "SELECT imie, nazwisko FROM pacjenci WHERE id_pacjenta = '$id_pacjenta'";

    // Wykonaj zapytanie
    $result = $con->query($sql);

    // Sprawdź, czy znaleziono wyniki
    if ($result->num_rows > 0) {
        // Pobierz pojedynczy wiersz wyników
        $row = $result->fetch_assoc();

        // Wypisz imię i nazwisko pacjenta
        echo $row['imie'] . ' ' . $row['nazwisko'];
    } else {
        // Jeśli nie znaleziono wyników, zwróć inny odpowiedni komunikat
        echo 'Brak danych pacjenta';
    }
}
?>
