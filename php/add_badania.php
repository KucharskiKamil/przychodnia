<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta']) && isset($_POST['data_badania']) && isset($_POST['rodzaj_badania']) && isset($_POST['wynik'])) {
    $id_pacjenta = $_POST['id_pacjenta'];
    $data_badania = $_POST['data_badania'];
    $rodzaj_badania = $_POST['rodzaj_badania'];
    $wynik = $_POST['wynik'];
    // Zapytanie SQL do dodania nowego wiersza do tabeli
    $sql = "INSERT INTO badania (id_pacjenta, data_badania, rodzaj_badania, wynik, id_lekarza) VALUES ('$id_pacjenta', '$data_badania', '$rodzaj_badania', '$wynik', '{$_SESSION["doctor_id"]}')";


    // Wykonaj zapytanie
    if ($con->query($sql) === TRUE)
    {
        echo "Nowy wiersz został dodany do tabeli recepty";
    } else {
        echo "Błąd podczas dodawania wiersza: " . mysqli_error($con);
    }
}
?>
