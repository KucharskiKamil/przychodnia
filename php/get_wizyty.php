<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta'])) {
    $id_pacjenta = $_POST['id_pacjenta'];

    // Zapytanie SQL do pobrania wizyt dla danego id_pacjenta
    $sql = "SELECT * FROM wizyty WHERE id_pacjenta = '$id_pacjenta'";

    // Wykonaj zapytanie
    $result = $con->query($sql);

    $wizyty = array();

    // Pobierz wyniki zapytania
    while ($row = $result->fetch_assoc()) {
        $wizyty[] = $row;
    }

    // Zwróć wyniki zapytania jako odpowiedź na żądanie AJAX
    echo json_encode($wizyty);
}
?>
