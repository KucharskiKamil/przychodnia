<?php
session_start();
include('connection.php');

if (isset($_POST['id_wizyty']) && isset($_POST['id_pacjenta']) && isset($_POST['data_wizyty']) && isset($_POST['czas_wizyty']) && isset($_POST['opis']) && isset($_POST['status'])) {
    $id_wizyty = $_POST['id_wizyty'];
    $id_pacjenta = $_POST['id_pacjenta'];
    $data_wizyty = $_POST['data_wizyty'];
    $czas_wizyty = $_POST['czas_wizyty'];
    $opis = $_POST['opis'];
    $status = $_POST['status'];
    if (empty(trim($data_wizyty)) && empty(trim($czas_wizyty)) && empty(trim($opis))) {
        $sql = "DELETE FROM wizyty WHERE id_wizyty = '$id_wizyty'";
    }
    else 
    {
        // Sprawdzenie poprawności formatu daty
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $data_wizyty);
        if ($datetime === false) {
            echo "error";
            exit;
        }
        // Sprawdzenie poprawności formatu czasu
        $time = strtotime($czas_wizyty);
        if ($time === false) 
        {
            echo "error";
            exit;
        }
        $sql = "UPDATE wizyty SET data_wizyty = '$data_wizyty', czas_wizyty = '$czas_wizyty', opis = '$opis', status ='$status' WHERE id_wizyty = '$id_wizyty'";
    }
    // Zapytanie
    
    // Wykonaj zapytanie
    $result = $con->query($sql);

    if ($result) {
        // Jeśli zapytanie wykonane poprawnie, zwróć sukces jako odpowiedź na żądanie AJAX
        echo "success";
    } else {
        // Jeśli wystąpił błąd podczas zapytania, zwróć informację o błędzie jako odpowiedź na żądanie AJAX
        echo "error";
    }
}

