<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta']) && isset($_POST['nazwa_choroby']) && isset($_POST['data_rozpoczecia']) && isset($_POST['data_zakonczenia'])) {
    $id_pacjenta = $_POST['id_pacjenta'];
    $nazwa_choroby = $_POST['nazwa_choroby'];
    $data_rozpoczecia = $_POST['data_rozpoczecia'];
    $data_zakonczenia = $_POST['data_zakonczenia'];
    $opis_choroby = $_POST['opis_choroby'];

    // Sprawdzenie, czy nazwa_choroby i data_rozpoczecia nie są puste
    if (!empty(trim($nazwa_choroby)) && !empty(trim($data_rozpoczecia))) {
        $data_zakonczenia_sql = !empty(trim($data_zakonczenia)) ? "'$data_zakonczenia'" : "NULL";
        $sql = "INSERT INTO historia_chorob (id_pacjenta, nazwa_choroby, data_rozpoczecia, data_zakonczenia, opis_choroby) VALUES ('$id_pacjenta', '$nazwa_choroby', '$data_rozpoczecia', $data_zakonczenia_sql, '$opis_choroby')";

        // Wykonaj zapytanie
        if ($con->query($sql) === TRUE) {
            echo "Nowy wiersz został dodany do tabeli historia_chorob";
        } else {
            echo "Błąd podczas dodawania wiersza: " . mysqli_error($con);
        }
    } else {
        echo "Nazwa choroby i data rozpoczęcia są wymagane";
    }
}
?>