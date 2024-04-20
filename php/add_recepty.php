<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta']) && isset($_POST['nazwa_leku']) && isset($_POST['ilosc_opakowan']) && isset($_POST['data_waznosci'])) {
    $id_pacjenta = $_POST['id_pacjenta'];
    $nazwa_leku = $_POST['nazwa_leku'];
    $ilosc_opakowan = $_POST['ilosc_opakowan'];
    $data_waznosci = $_POST['data_waznosci'];
    $data_wystawienia = date("Y-m-d");
    // Zapytanie SQL do dodania nowego wiersza do tabeli
    $sql = "INSERT INTO recepty (id_pacjenta, nazwa_leku, ilosc_opakowan, data_waznosci, data_wystawienia) VALUES ('$id_pacjenta', '$nazwa_leku', '$ilosc_opakowan', '$data_waznosci', '$data_wystawienia')";

    // Wykonaj zapytanie
    if ($con->query($sql) === TRUE) {
        echo "Nowy wiersz został dodany do tabeli recepty";
    } else {
        echo "Błąd podczas dodawania wiersza: " . mysqli_error($con);
    }
}
?>
