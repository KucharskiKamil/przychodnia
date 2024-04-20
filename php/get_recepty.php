<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta'])) {
    $id_pacjenta = $_POST['id_pacjenta'];

    // Zapytanie SQL do pobrania wizyt dla danego id_pacjenta
    $sql = "SELECT id_recepty,nazwa_leku,ilosc_opakowan,data_waznosci FROM recepty WHERE id_pacjenta = '$id_pacjenta'";

    // Wykonaj zapytanie
    $result = $con->query($sql);

    $recepty = array();

    // Pobierz wyniki zapytania
    while ($row = $result->fetch_assoc()) {
        $recepty[] = $row;
    }

    // Zwróć wyniki zapytania jako odpowiedź na żądanie AJAX
    echo json_encode($recepty);
}
?>
