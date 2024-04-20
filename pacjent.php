<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.ico">
    <!-- Link do ikony na inforBarzee-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,500,0,0" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- My custom styles -->
    <link rel="stylesheet" href="./styles/mystyles.css">
    <title>Przychodnia MediHelp</title>

</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['logged_in']) || (isset($_SESSION['logged_in']) && $_SESSION['is_doctor'] != false)) {
        echo 'Nie masz uprawnień do wyświetlania tej strony!';
        exit();
    }
    ?>
    <div id="infoBar">
        <span id="leftSegment">
            <span id="spaceLR"></span>
            <span id="leftElement1">
                <span class="material-symbols-outlined">
                    location_on
                </span>
                Adres: Białystok ul. Rzemieślnicza 23
            </span>
            <span id="leftElement2">
                <span class="material-symbols-outlined">
                    call
                </span>
                Zadzwoń: +48 123 456 789
            </span>
            <span id="leftElement3">
                <span class="material-symbols-outlined">
                    mail
                </span>
                Email: nieodpisujemy@wcale.com
            </span>
        </span>
        <span id="rightSegment">
            <span id="rightElement1"></span>
            <?php

            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                echo '<form method="post" action="php/logout.php">
                        <input type="hidden" name="logout" value="true">
                        <button type="submit" id="logoutBtn">
                            <span class="material-symbols-outlined">logout</span>Wyloguj
                        </button>
                    </form>';
            }
            if (!isset($_SESSION['logged_in'])) {

                echo '<button id="loginBtn" onclick="document.getElementById(\'id01\').style.display=\'block\'">Logowanie</button>';
            }
            ?>
            <span id="spaceLR"></span>
        </span>
    </div>

    <div id="navBar">
        <span id="spaceLR"></span>
        <span id="navLeftSegment">
            <a href="index.php">MediHelp</a>
        </span>
        <span class="navRightSegment" id="navRightSegment">
            <a class="navLink" href="index.php">Główna</a>

            <a class="navLink" href="#lastDiv">O nas</a>
            <a class="navLink" href="#lastDiv">Doktorzy</a>
            <a class="navLink" href="#lastDiv">Kontakt</a>
            <a href="javascript:void(0);" class="icon" onclick="showHideBar()"><span
                    class="material-symbols-outlined">menu</span></a>


        </span>
        <span id="spaceLR"></span>
    </div>
    <div id="hiddenMenuForNav">
        <a class="hiddenLink" href="index.php">Główna</a>
        <a class="hiddenLink">O nas</a>
        <a class="hiddenLink">Doktorzy</a>
        <a class="hiddenLink" href="index.php#dol">Kontakt</a>
    </div>
    <?php
    echo '<div style="background-color: rgb(0, 83, 211);height:36px;"></div>';
    ?>
    <div id="lewyElement">
        <span class="lewyElementL"></span>
        <div class="lewyElementKontent">
            <div id="LEKinfoDisplay">
                <img alt="anynonymmousProfilePicture" src="./images/anynomous.png" height="50%" style="max-width:100px;max-height:100px;">
                <span id="LEKIDimieNazwisko">
                    <?php
                    echo $_SESSION['first_name'].' '.$_SESSION['last_name'];
                    ?>
                </span>
            </div>
            <div class="LEKspis">
                <a href="#badania"><span class="material-symbols-outlined">overview</span>Badania</a>
                <a href="#choroby"><span class="material-symbols-outlined">medical_mask</span>Historia chorób</a>
                <a href="#recepty"><span class="material-symbols-outlined">pill</span>Recepty</a>
                <a href="#wizyty"><span class="material-symbols-outlined">home_health</span>Wizyty</a>
            </div>
            <div class="LEKreszta">
                <form method="post" action="php/logout.php">
                    <input type="hidden" name="logout" value="true">
                    <button type="submit" id="LEKwyloguj"><span class="material-symbols-outlined">logout</span>Wyloguj</button>
                </form>
            </div>
        </div>
        <span class="lewyElementR"></span>
    </div>
    <div class="pacjentPierwszyDiv">
        <div id="pacjentPrawaStrona">
                <?php
                    include("./php/connection.php");
                    $zapytanie = "SELECT * FROM badania WHERE id_pacjenta = {$_SESSION['patient_id']} ORDER BY data_badania DESC";
                    $result = mysqli_query($con, $zapytanie);
                    echo"<div id='pacjentTablicaHolder'><div id='divPacjentTablica'><span id='badania'>Twoje badania</span></div><table id='pacjentTablica'>";
                    if (mysqli_num_rows($result) > 0)
                    {
                        echo "
                        <tr class='tabelaPacjentHeadery'>
                        <th>Data badania</th><th>Lekarz</th><th>Rodzaj badania</th><th>Wynik</th>
                        </tr>";
                        while ($row = mysqli_fetch_assoc($result)) 
                        {
                            $data = date("d.m.Y", strtotime($row['data_badania']));
                            $drugiezapytanie = "SELECT imie, nazwisko FROM lekarze WHERE id_lekarza = {$row['id_lekarza']}";
                            $rezultat = mysqli_query($con,$drugiezapytanie);
                            if (mysqli_num_rows($rezultat) == 1)
                            {
                                $row2 = mysqli_fetch_assoc($rezultat);
                                echo "<tr><td class='poleTabeliPacjenta'>" . $data . "</td><td class='poleTabeliPacjenta'>" . $row2['imie'] ." ".$row2['nazwisko'] ."</td><td class='poleTabeliPacjenta'>" . $row['rodzaj_badania'] . "</td><td class='poleTabeliPacjenta'>" . $row['wynik'] . "</td></tr>";
                            }
                            else
                            {
                                echo "<tr><td class='poleTabeliPacjenta'>" . $data . "</td><td class='poleTabeliPacjenta'>Błąd ze znalezieniem lekarza!</td><td class='poleTabeliPacjenta'>" . $row['rodzaj_badania'] . "</td><td class='poleTabeliPacjenta'>" . $row['wynik'] . "</td></tr>";
                            }
                            
                        }
                        
                    } 
                    else 
                    {
                        echo "
                        <tr class='tabelaPacjentHeaderyError'>
                        <th>Nie znaleziono żadnych badań!</th>
                        </tr>";
                    }
                    echo "</table></div>";

                    $zapytanie = "SELECT * FROM historia_chorob WHERE id_pacjenta = {$_SESSION['patient_id']} ORDER BY data_rozpoczecia DESC";
                    $result = mysqli_query($con, $zapytanie);
                    echo"<div id='pacjentTablicaHolder'><div id='divPacjentTablica'><span id='choroby'>Twoja historia chorób</span></div><table id='pacjentTablica'>";
                    if (mysqli_num_rows($result) > 0)
                    {
                        echo "<tr class='tabelaPacjentHeadery'>
                        <th>Nazwa choroby</th><th>Data rozpoczęcia</th><th>Data zakończenia</th><th>Opis</th>
                        </tr>";
                        while ($row = mysqli_fetch_assoc($result)) 
                        {
                            echo "<tr><td class='poleTabeliPacjenta'>" .$row['nazwa_choroby']. "</td><td class='poleTabeliPacjenta'>" .$row['data_rozpoczecia']."</td><td class='poleTabeliPacjenta'>" .$row['data_rozpoczecia']. "</td><td class='poleTabeliPacjenta'>" . $row['opis_choroby'] . "</td></tr>";
                        }
                    } 
                    else 
                    {
                        echo "
                        <tr class='tabelaPacjentHeaderyError'>
                        <th>Nie znaleziono żadnych chorób!</th>
                        </tr>";
                    }
                    echo"</table></div>";



                    $zapytanie = "SELECT * FROM recepty WHERE id_pacjenta = {$_SESSION['patient_id']} ORDER BY data_wystawienia DESC";
                    $result = mysqli_query($con, $zapytanie);
                    echo"<div id='pacjentTablicaHolder'><div id='divPacjentTablica'><span id='recepty'>Twoje recepty</span></div><table id='pacjentTablica'>";
                    if (mysqli_num_rows($result) > 0)
                    {
                        echo "
                        <tr class='tabelaPacjentHeadery'>
                        <th>Nazwa leku</th><th>Ilość opakowań</th><th>Data zakończenia recepty</th>
                        </tr>";
                        while ($row = mysqli_fetch_assoc($result)) 
                        {
                            echo "<tr><td class='poleTabeliPacjenta'>" .$row['nazwa_leku']. "</td><td class='poleTabeliPacjenta'>" .$row['ilosc_opakowan']."</td><td class='poleTabeliPacjenta'>" .$row['data_waznosci']. "</td></tr>";
                        }
                        
                    } 
                    else 
                    {
                        echo "
                        <tr class='tabelaPacjentHeaderyError'>
                        <th>Nie znaleziono żadnych recept!</th>
                        </tr>";
                    }
                    echo "</table></div>";

                    $zapytanie = "SELECT * FROM wizyty WHERE id_pacjenta = {$_SESSION['patient_id']} ORDER BY data_wizyty DESC";
                    $result = mysqli_query($con, $zapytanie);
                    echo"<div id='pacjentTablicaHolder'><div id='divPacjentTablica'><span id='wizyty'>Twoje wizyty</span></div><table id='pacjentTablica'>";
                    if (mysqli_num_rows($result) > 0){
                        echo "
                        <tr class='tabelaPacjentHeadery'>
                        <th>Data wizyty</th><th>Czas wizyty</th><th>Opis wizyty</th><th>Status wizyty</th>
                        </tr>";
                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $i++;
                            $czas_arr = explode(":", $row['czas_wizyty']);
                            $godziny = intval($czas_arr[0]);
                            $minuty = intval($czas_arr[1]);
                            $sekundy = intval($czas_arr[2]);
                            $formatowany_czas = "";
                            if ($godziny > 0) {
                                if ($godziny == 1) {
                                    $formatowany_czas .= $godziny . " godzina";
                                } else if ($godziny > 1 && $godziny < 5) {
                                    $formatowany_czas .= $godziny . " godziny";
                                } else {
                                    $formatowany_czas .= $godziny . " godzin";
                                }
                            }
                            if ($minuty > 0) {
                                if ($formatowany_czas != "") {
                                    $formatowany_czas .= " ";
                                }
                                $formatowany_czas .= $minuty . " minut";
                            }
                            $data = date("H:i d.m.Y", strtotime($row['data_wizyty']));
                            if ($row['status'] == 'oczekująca') {
                                echo "<tr><td class='poleTabeliPacjenta'>" . $data . "</td><td class='poleTabeliPacjenta'>" . $formatowany_czas . "</td><td class='poleTabeliPacjenta'>" . $row['opis'] . "</td><td class='poleTabeliPacjenta poleWizytyOczekujaca'>" . $row['status'] . "</td></tr>";
                            } else if ($row['status'] == 'odbyta') {
                                echo "<tr><td class='poleTabeliPacjenta'>" . $data . "</td><td class='poleTabeliPacjenta'>" . $formatowany_czas . "</td><td class='poleTabeliPacjenta'>" . $row['opis'] . "</td><td class='poleTabeliPacjenta poleWizytyOdbyta'>" . $row['status'] . "</td></tr>";
                            } else {
                                echo "<tr><td class='poleTabeliPacjenta'>" . $data . "</td><td class='poleTabeliPacjenta'>" . $formatowany_czas . "</td><td class='poleTabeliPacjenta'>" . $row['opis'] . "</td><td class='poleTabeliPacjenta poleWizytyNiePrzyszedl'>" . $row['status'] . "</td></tr>";
                            }
                        }
                        
                    } else {
                        echo "
                        <tr class='tabelaPacjentHeaderyError'>
                        <th>Nie znaleziono żadnych wizyt!</th>
                        </tr>";
                    }
                    echo "</table></div>";
                ?>
                <div style="height:50px;"></div>
        </div>
    </div>
    
    <!--    DIV Z INFORMACJAMI I LINKAMI-->
    <div id="lastDiv">
        <span id="spaceLR"></span>
        <div class="lDLinks">
            <span class="lDAbout">Linki</span>
            <a href="index.php"><span class="material-symbols-outlined">arrow_right_alt</span>Strona główna</a>
            <a href="#lastDiv"><span class="material-symbols-outlined">arrow_right_alt</span>O nas</a>
            <a href="index.php"><span class="material-symbols-outlined">arrow_right_alt</span>Jakiś trzeci link</a>
        </div>
        <div class="lDServises">
            <span class="lDAbout">Usługi</span>
            <span class="lDSUslugi">Służby ratunkowe</span>
            <span class="lDSUslugi">Wykwalifikowani lekarze</span>
            <span class="lDSUslugi">Specjalistyczna opieka</span>
            <span class="lDSUslugi">Usługi 24h na dobę</span>
        </div>
        <div class="lDInfo">
            <span class="lDAbout">Masz pytanie?</span>
            <span class="lDALocation">
                <span class="material-symbols-outlined">location_on</span>
                <span class="lDALPlace">ul. Rzemieślnicza 23<br>15-879 Białystok</span>
            </span>
            <span class="lDAPhone">
                <span class="material-symbols-outlined">call</span>
                +48 123 456 789
            </span>
            <span class="lDAEmail">
                <span class="material-symbols-outlined">mail</span>
                nieodpisujemy@wcale.com
            </span>
        </div>
        <span id="spaceLR"></span>
    </div>


    <div class="veryBottom">Projekt wykonany przez: Kamil Kucharski 313109</div>
</body>
<!-- Optional JavaScript -->
<script src="./scripts/myscripts.js"></script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

</html>