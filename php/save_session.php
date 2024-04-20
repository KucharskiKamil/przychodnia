<?php
session_start();

if (isset($_POST['userId'])) {
    $id = $_POST['userId'];
    $_SESSION['user_id'] = $id;

    // Zwróć wartość zmiennej sesji jako odpowiedź na żądanie AJAX
    echo $_SESSION['user_id'];
}
?>
