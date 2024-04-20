<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta']) && isset($_POST['data_wizyty']) && isset($_POST['czas_wizyty']) && isset($_POST['opis']) && isset($_POST['status'])) 
{
    $id_pacjenta = $_POST['id_pacjenta'];
    $data_wizyty = $_POST['data_wizyty'];
    $czas_wizyty = $_POST['czas_wizyty'];
    $opis = $_POST['opis'];
    $status = $_POST['status'];
    
    // Sprawdzenie poprawności formatu daty
    // Sprawdzenie poprawności formatu daty
    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $data_wizyty);
    if ($datetime === false) 
    {
        echo "error";
        exit;
    }
    // Zapytanie SQL do dodania nowego wiersza do tabeli
    $sql = "INSERT INTO wizyty (id_pacjenta, data_wizyty, czas_wizyty, opis, id_lekarza, status) VALUES ('$id_pacjenta', '$data_wizyty', '$czas_wizyty', '$opis', '{$_SESSION["doctor_id"]}', '$status')";

    // Wykonaj zapytanie
    if ($con->query($sql) === TRUE)
    {
        echo "Nowy wiersz został dodany do tabeli recepty";
    } else {
        echo "Błąd podczas dodawania wiersza: " . mysqli_error($con);
    }
}

