<?php
session_start();
include('connection.php');

if (isset($_POST['id_pacjenta'])) {
    $id_pacjenta = $_POST['id_pacjenta'];

    // Zapytanie SQL do usuwania wierszy z tabeli "wizyty"
    $sql_wizyty = "DELETE FROM wizyty WHERE id_pacjenta = '$id_pacjenta'";

    // Zapytanie SQL do usuwania wierszy z tabeli "recepty"
    $sql_recepty = "DELETE FROM recepty WHERE id_pacjenta = '$id_pacjenta'";

    // Zapytanie SQL do usuwania wierszy z tabeli "badania"
    $sql_badania = "DELETE FROM badania WHERE id_pacjenta = '$id_pacjenta'";

    // Zapytanie SQL do usuwania wierszy z tabeli "choroby"
    $sql_choroby = "DELETE FROM choroby WHERE id_pacjenta = '$id_pacjenta'";

     // Zapytanie SQL do usuwania wierszy z tabeli "choroby"
    $sql_users = "DELETE FROM users WHERE id_pacjenta = '$id_pacjenta'";

     // Zapytanie SQL do usuwania wierszy z tabeli "choroby"
    $sql_pacjenci = "DELETE FROM pacjenci WHERE id_pacjenta = '$id_pacjenta'";

    // Wykonaj zapytania
    $result_wizyty = $con->query($sql_wizyty);
    $result_recepty = $con->query($sql_recepty);
    $result_badania = $con->query($sql_badania);
    $result_choroby = $con->query($sql_choroby);
    $result_users = $con->query($sql_users);
    $result_pacjenci = $con->query($sql_pacjenci);

    // Sprawdź wyniki zapytań
    if ($result_wizyty && $result_recepty && $result_badania && $result_choroby && $result_users && $result_pacjenci) {
        // Jeśli wszystkie zapytania wykonane poprawnie, zwróć sukces jako odpowiedź na żądanie AJAX
        echo "success";
    } else {
        // Jeśli wystąpił błąd podczas któregoś z zapytań, zwróć informację o błędzie jako odpowiedź na żądanie AJAX
        echo "error";
    }
}
?>
