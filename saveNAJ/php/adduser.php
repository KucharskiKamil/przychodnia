<?php
session_start();
include('connection.php');

$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$pesel = $_POST['pesel'];
$haslo = $_POST['haslo'];
$email = $_POST['email'];

// To prevent SQL injection
$imie = mysqli_real_escape_string($con, $imie);
$nazwisko = mysqli_real_escape_string($con, $nazwisko);
$pesel = mysqli_real_escape_string($con, $pesel);
if(strlen($pesel)==11)
{

    $sql_sprawdzenie = "SELECT * FROM pacjenci where pesel =$pesel";
    $rezultat_sprawdzenia = mysqli_query($con,$sql_sprawdzenie);
    if($rezultat_sprawdzenia)
    {
        if (mysqli_num_rows($rezultat_sprawdzenia) > 0) 
        {
            $_SESSION['addUserError'] = "Pacjent z podanym PESELEM już jest zarejestrowany!";
            header("Location: ../doktor.php");
            exit();
        }
        else //Podanego pacjenta nie ma w bazie więc go wpisujemy
        {
            $sql = "INSERT INTO pacjenci (imie, nazwisko, pesel) VALUES ('$imie', '$nazwisko', '$pesel')";
            $result = mysqli_query($con, $sql);
            
            if ($result)
            {
                $sql2 = "SELECT id_pacjenta FROM pacjenci WHERE pesel = '$pesel'";
                $result2 = mysqli_query($con, $sql2);
                if ($result2) 
                {
                    $row = mysqli_fetch_assoc($result2);
                    if($row) 
                    {
                        $id_pacjenta = $row['id_pacjenta'];
                        $sql3 = "INSERT INTO users (email, password, is_doctor, id_doktora, id_pacjenta) VALUES ('$email', '$haslo', 0, NULL, $id_pacjenta)";
                        if (mysqli_query($con, $sql3)) 
                        {
                            // User added successfully
                            $_SESSION['notification']="Pomyślnie dodano użytkownika do bazy pacjentów!";
                            header("Location: ../doktor.php");
                            exit();
                        } else 
                        {
                            $_SESSION['addUserError']="Błąd przy dodawaniu pacjenta do tabeli users!";
                            header("Location: ../doktor.php");
                            exit();
                        }
                    } 
                    else 
                    {
                        $_SESSION['addUserError']="Pacjent istnieje, ale nie ma przypisanego ID!";
                        header("Location: ../doktor.php");
                        exit();
                    }
                } 
                else 
                {
                    $_SESSION['addUserError']="Błąd w wyszukiwaniu ID dodanego pacjenta!";
                    header("Location: ../doktor.php");
                    exit();
                }


                

            } 
            else 
            {
                // Error occurred while adding user
                $_SESSION['addUserError']="Błąd w dodawaniu użytkownika do bazy pacjentów!!";
                header("Location: ../doktor.php");
                exit();
            }
        }
    }
    else
    {
        $_SESSION['addUserError'] = "Błąd podczas sprawdzania czy dany pacjent jest już wpisany!";
        header("Location: ../doktor.php");
        exit();
    }
}
else
{
    $_SESSION['addUserError'] = "Podano zły pesel! Powinien mieć 11 cyfr!";
    header("Location: ../doktor.php");
    exit();
}

?>