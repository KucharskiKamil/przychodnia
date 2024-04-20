<?php
session_start(); 

if(isset($_POST['logout']) && $_POST['logout'] == 'true') {
    unset($_SESSION['email']);
    unset($_SESSION['is_doctor']);
    unset($_SESSION['user_id']);
    unset($_SESSION['doctor_id']);
    unset($_SESSION['patient_id']);
    unset($_SESSION['logged_in']);
    unset($_SESSION['error']);
    unset($_SESSION['first_name']);
    unset($_SESSION['last_name']);
    unset($_SESSION['notification']);
    unset($_SESSION['addUserError']);
    header("Location: ../index.php");
}
?>