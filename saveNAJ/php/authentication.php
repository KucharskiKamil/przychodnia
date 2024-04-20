<?php      
    session_start();
    include('connection.php');  
    $email = $_POST['email'];
    $password = $_POST['psw'];  
    
        //to prevent from mysqli injection  
        $email = stripcslashes($email);  
        $password = stripcslashes($password);  
        $email = mysqli_real_escape_string($con, $email);  
        $password = mysqli_real_escape_string($con, $password);  
    
        $sql = "select *from users where email = '$email' and password = '$password'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);
        
        if($count == 1)
        {
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id']=$row['id'];
            $_SESSION['is_doctor'] = $row['is_doctor'];
            if($_SESSION['is_doctor']==1)
            {
                $_SESSION['doctor_id']=$row['id_doktora'];
                $zapytanie = "SELECT imie, nazwisko FROM lekarze WHERE id_lekarza = " . $_SESSION['doctor_id'];
                $result = mysqli_query($con, $zapytanie);
                $row = mysqli_fetch_assoc($result);
                $_SESSION['first_name'] = $row['imie'];
                $_SESSION['last_name'] = $row['nazwisko'];
            }
            else
            {
                $_SESSION['patient_id']=$row['id_pacjenta'];
                $zapytanie = "SELECT imie, nazwisko FROM pacjenci WHERE id_pacjenta = " . $_SESSION['patient_id'];
                $result = mysqli_query($con, $zapytanie);
                $row = mysqli_fetch_assoc($result);
                $_SESSION['first_name'] = $row['imie'];
                $_SESSION['last_name'] = $row['nazwisko'];
            }
            $_SESSION['logged_in'] = true;
            unset($_SESSION['error']);
            header("Location: ../index.php");
            exit();
        }
        else
        {
            $_SESSION['error']="Nieprawidłowy email lub hasło";
            header("Location: ../index.php");
            exit();
        }
?>