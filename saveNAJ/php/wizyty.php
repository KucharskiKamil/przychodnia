<?php
session_start();

if (isset($_POST['id_pacjenta'])) {
    $_SESSION['selectedPatient'] = $_POST['id_pacjenta'];
}

// Tutaj wykonaj zapytanie do bazy danych na podstawie $_SESSION['selectedPatient']
// Np. SELECT * FROM wizyty WHERE id_pacjenta = $_SESSION['selectedPatient']
// Przetwarzanie wyniku zapytania i zwrócenie odpowiedzi

?>
