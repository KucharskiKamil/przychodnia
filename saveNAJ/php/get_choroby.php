<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta'])) {
    $id_pacjenta = $_POST['id_pacjenta'];

    // Zapytanie SQL do pobrania wizyt dla danego id_pacjenta
    $sql = "SELECT * FROM historia_chorob WHERE id_pacjenta = '$id_pacjenta'";

    // Wykonaj zapytanie
    $result = $con->query($sql);

    $choroby = array();

    // Pobierz wyniki zapytania
    while ($row = $result->fetch_assoc()) {
        $choroby[] = $row;
    }

    // Zwróć wyniki zapytania jako odpowiedź na żądanie AJAX
    echo json_encode($choroby);
}
?>
