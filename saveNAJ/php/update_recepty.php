<?php
session_start();
include('connection.php');

if (isset($_POST['id_recepty']) && isset($_POST['nazwa_leku']) && isset($_POST['ilosc_opakowan']) && isset($_POST['data_waznosci'])) {
    $id_recepty = $_POST['id_recepty'];
    $nazwa_leku = $_POST['nazwa_leku'];
    $ilosc_opakowan = $_POST['ilosc_opakowan'];
    $data_waznosci = $_POST['data_waznosci'];
    if (empty(trim($nazwa_leku)) && empty(trim($ilosc_opakowan)) && empty(trim($data_waznosci))) 
    {
        $sql = "DELETE FROM recepty WHERE id_recepty = '$id_recepty'";
    } 
    else 
    {
        $sql = "UPDATE recepty SET nazwa_leku = '$nazwa_leku', ilosc_opakowan = '$ilosc_opakowan', data_waznosci = '$data_waznosci' WHERE id_recepty = '$id_recepty'";
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
