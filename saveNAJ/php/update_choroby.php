<?php
session_start();
include('connection.php');

if (isset($_POST['id_historii']) && isset($_POST['nazwa_choroby']) && isset($_POST['data_rozpoczecia'])) {
    $id_historii = $_POST['id_historii'];
    $nazwa_choroby = $_POST['nazwa_choroby'];
    $data_rozpoczecia = $_POST['data_rozpoczecia'];
    $data_zakonczenia = $_POST['data_zakonczenia'];
    $opis_choroby = $_POST['opis_choroby'];

    // Utwórz zapytanie SQL
    if (empty(trim($nazwa_choroby)) && empty(trim($data_rozpoczecia)) && empty(trim($opis_choroby)) && empty(trim($data_zakonczenia))) {
        $sql = "DELETE FROM historia_chorob WHERE id_historii = '$id_historii'";
    } 
    else 
    {
        $data_zakonczenia_sql = !empty(trim($data_zakonczenia)) ? "'$data_zakonczenia'" : "NULL";
        $sql = "UPDATE historia_chorob SET nazwa_choroby = '$nazwa_choroby', data_rozpoczecia = '$data_rozpoczecia', data_zakonczenia = $data_zakonczenia_sql, opis_choroby ='$opis_choroby' WHERE id_historii = '$id_historii'";
    }
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
?>
