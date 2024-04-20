<?php
session_start();
include('connection.php');

if (isset($_POST['id_badania']) && isset($_POST['rodzaj_badania']) && isset($_POST['data_badania']) && isset($_POST['wynik'])) {
    $id_badania = $_POST['id_badania'];
    $data_badania = $_POST['data_badania'];
    $rodzaj_badania = $_POST['rodzaj_badania'];
    $wynik = $_POST['wynik'];
    if (empty(trim($data_badania)) && empty(trim($rodzaj_badania)) && empty(trim($wynik))) 
    {
        $sql = "DELETE FROM badania WHERE id_badania = '$id_badania'";
    } 
    else 
    {
        $sql = "UPDATE badania SET data_badania = '$data_badania', rodzaj_badania = '$rodzaj_badania', wynik = '$wynik' WHERE id_badania = '$id_badania'";
    }
    // Wykonaj zapytanie
    $result = $con->query($sql);

    if ($result) 
    {
        // Jeśli zapytanie wykonane poprawnie, zwróć sukces jako odpowiedź na żądanie AJAX
        echo "success";
    } else {
        // Jeśli wystąpił błąd podczas zapytania, zwróć informację o błędzie jako odpowiedź na żądanie AJAX
        echo "error";
    }
}
?>
