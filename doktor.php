<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.ico">
    <!-- Link do ikon google-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,500,0,0" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Mój css-->
    <link rel="stylesheet" href="./styles/mystyles.css">
    <title>Przychodnia MediHelp</title>
    <!-- Moj JavaScript oraz AJAX-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Moje 2 skrypty, pierwszy ogólny, drugi wyłącznie do wyszukiwania pacjenta -->
    <script src="./scripts/pacjentSearchingBar.js"></script>
    <script src="./scripts/myscripts.js"></script>
</head>
<body>
    <?php
    session_start();
    // Jeśli nie jest zalogowanym doktorem to nie pozwól na dostęp do strony
    if (!isset($_SESSION['logged_in']) || (isset($_SESSION['logged_in']) && $_SESSION['is_doctor'] != true)) {
        echo 'Nie masz uprawnień do wyświetlania tej strony!';
        exit();
    }
    ?>
    <!-- Górna belka z informacjami -->
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
            <form method="post" action="php/logout.php">
                <input type="hidden" name="logout" value="true">
                <button type="submit" id="logoutBtn">
                    <span class="material-symbols-outlined">logout</span>Wyloguj
                </button>
            </form>
            <span id="spaceLR"></span>
        </span>
    </div>
    <!-- Panel nawigacyjny -->
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
    <!-- Panel nawigacyjny, ale ten kiedy strona jest zbyt mała żeby wyświetlić ten większy (czyli dla telefonów) -->
    <div id="hiddenMenuForNav">
        <a class="hiddenLink" href="index.php">Główna</a>
        <a class="hiddenLink">O nas</a>
        <a class="hiddenLink">Doktorzy</a>
        <a class="hiddenLink" href="index.php#dol">Kontakt</a>
    </div>
    <div style="background-color: rgb(0, 83, 211);height:36px;"></div>
    <div id="paneleDoktora">
        <!-- Panel informacji o Doktorze, ten po lewo -->
        <div id="lewyPanelDoktora">
            <?php
            echo '<div id="daneDoktora">Dr ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</div>';
            ?>
            <button id="btnPokazStatystyki" class="przyciskDoktora" onclick="zmienStatystyki()"><span
                    class="material-symbols-outlined">query_stats</span><span class="tekstPrzycisku">Pokaż
                    statystyki</span></button>
            <button id="btnDodajPacjenta" class="przyciskDoktora"
                onclick="document.getElementById('dodajUzytkownika').style.display='block';"><span
                    class="material-symbols-outlined">person_add</span><span class="tekstPrzycisku">Dodaj
                    pacjenta</span></button>
            <button id="btnWyszukajPacjenta" class="przyciskDoktora przyciskDoktoraAktywny"
                onclick="zmienWyszukaj()"><span class="material-symbols-outlined">person_search</span><span
                    class="tekstPrzycisku">Wyszukaj
                    pacjenta</span></button>
        </div>
        <div id="prawyPanelDoktora">
            <!-- Div odpowiadający za wyświetlanie statystyk -->
            <div id="statyKontener">
                <!-- Wszyscy pacjenci -->
                <div class="statyItem" style="background-color:#00c1ee;">
                    <div class="statyItemText">
                        <?php
                        include("./php/connection.php");
                        $sql = "SELECT COUNT(*) AS liczba_pacjentow FROM pacjenci";
                        $result = mysqli_query($con, $sql);
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $liczba_pacjentow = $row["liczba_pacjentow"];
                        } else {
                            $liczba_pacjentow = 0;
                        }
                        echo "<h2>" . $liczba_pacjentow . "</h2>";
                        ?>
                        <p style="margin-left:-40px;">Wszyscy pacjenci</p>
                    </div>
                    <div class="statyItemIcon">
                        <span class="material-symbols-outlined">bar_chart_4_bars</span>
                    </div>
                    <div class="statyItemDol" style="background-color:#0697b8;">
                        <button onclick="ukryjSchowajPacjenci()" id="btnPacjenci"><span>Dowiedz się
                                więcej</span>&nbsp;<span
                                class="material-symbols-outlined">expand_circle_down</span></button>
                    </div>
                </div>
                <!-- Wszyscy pacjenci - rozszerzenie -->
                <div class="tabelaStatyHolder" id="tabelaStatyHolderPacjenci" style="display:none;">
                    <div class="tabelaPodStaty" id="tabelaPodStatyPacjenci">
                        <?php
                        $sql = "SELECT * FROM pacjenci";
                        $result = mysqli_query($con, $sql);

                        // Wyświetlenie wyników na stronie
                        if ($result->num_rows > 0) {
                            echo '<table class="tabelaDoktora">';
                            echo '<thead>';
                            echo '<tr><th class="tabelaDoktoraHeaderLewy">ID</th><th class="tabelaDoktoraSrodek">Imię</th><th class="tabelaDoktoraSrodek">Nazwisko</th><th class="tabelaDoktoraHeaderPrawy">Pesel</th></tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='poleTabeliDoktora'>" . ($i) . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["imie"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["nazwisko"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["pesel"] . "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            echo '</tbody>';
                            echo '</table>';
                        }
                        ?>
                    </div>
                </div>

                <div id="doktorSpacja1"></div>
                <!-- Wizyty danego doktora -->
                <div class="statyItem" style="background-color:#f29c10;">
                    <div class="statyItemText">
                        <?php

                        $sql = "SELECT COUNT(*) AS liczba_wizyt FROM wizyty WHERE id_lekarza = " . $_SESSION['doctor_id'] . " AND data_wizyty >= CURDATE();";
                        $result = mysqli_query($con, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<h2>" . $row['liczba_wizyt'] . "</h2>";
                        } else {
                            echo "<h2>0</h2>";
                        }

                        ?>
                        <p style="margin-left:-80px;">Twoje wizyty</p>
                    </div>
                    <div class="statyItemIcon">
                        <span class="material-symbols-outlined">empty_dashboard</span>
                    </div>
                    <div class="statyItemDol" style="background-color:#bb7607;">
                        <button onclick="ukryjSchowajWizyty()" id="btnWizyty"><span>Dowiedz się więcej</span>&nbsp;<span
                                class="material-symbols-outlined">expand_circle_down</span></button>
                    </div>
                </div>
                <!-- Wizyty danego doktora - rozszerzenie -->
                <div class="tabelaStatyHolder" id="tabelaStatyHolderWizyty" style="display:none;">
                    <div class="tabelaPodStaty" id="tabelaPodStatyWizyty">
                        <?php
                        $sql = "SELECT * FROM wizyty WHERE id_lekarza=" . $_SESSION['doctor_id'] . " AND data_wizyty >= CURDATE() ORDER BY data_wizyty DESC";
                        $result = mysqli_query($con, $sql);

                        // Wyświetlenie wyników na stronie
                        if ($result->num_rows > 0) {
                            echo '<table class="tabelaDoktora">';
                            echo '<tbody>';
                            echo '<tr><th class="tabelaDoktoraHeaderLewy">Data wizyty</th><th class="tabelaDoktoraSrodek">Czas wizyty</th><th class="tabelaDoktoraSrodek">Pacjent</th><th class="tabelaDoktoraHeaderPrawy">Opis</th></tr>';
                            while ($row = $result->fetch_assoc()) {
                                $sql2 = "SELECT CONCAT(imie, ' ', nazwisko) AS pelne_imie FROM pacjenci WHERE id_pacjenta = " . $row['id_pacjenta'];
                                $result2 = mysqli_query($con, $sql2);
                                $pelne_imie;
                                if ($result2) {
                                    if (mysqli_num_rows($result2) > 0) {
                                        while ($row2 = mysqli_fetch_assoc($result2)) {
                                            $pelne_imie = $row2['pelne_imie'];
                                        }
                                    } else {
                                        $pelne_imie = "Brak danych pacjenta!";
                                    }
                                } else {
                                    $pelne_imie = "Błąd pozyskiwania danych!";
                                }

                                echo "<tr>";
                                echo "<td class='poleTabeliDoktora'>" . $row["data_wizyty"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["czas_wizyty"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $pelne_imie . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["opis"] . "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            echo '</tbody>';
                            echo '</table>';
                        }
                        ?>
                    </div>
                </div>
                <div id="doktorSpacja2"></div>
                <!-- Badania -->
                <div class="statyItem" style="background-color:#de4b39;">
                    <div class="statyItemText">
                        <?php

                        $sql = "SELECT COUNT(*) AS liczba_badan FROM badania";
                        $result = mysqli_query($con, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<h2>" . $row['liczba_badan'] . "</h2>";
                        } else {
                            echo "<h2>0</h2>";
                        }

                        ?>
                        <p style="margin-left:-110px;">Badania</p>
                    </div>
                    <div class="statyItemIcon">
                        <span class="material-symbols-outlined">labs</span>
                    </div>
                    <div class="statyItemDol" style="background-color:#bf2e1d;">
                        <button onclick="ukryjSchowajBadania()" id="btnBadania"><span>Dowiedz się
                                więcej</span>&nbsp;<span
                                class="material-symbols-outlined">expand_circle_down</span></button>
                    </div>
                </div>
                <!-- Badania - rozszerzenie -->
                <div class="tabelaStatyHolder" id="tabelaStatyHolderBadania" style="display:none;">
                    <div class="tabelaPodStaty" id="tabelaPodStatyBadania">
                        <?php
                        $sql = "SELECT * FROM badania";
                        $result = mysqli_query($con, $sql);

                        // Wyświetlenie wyników na stronie
                        if ($result->num_rows > 0) {
                            echo '<table class="tabelaDoktora">';
                            echo '<tbody>';
                            echo '<tr><th class="tabelaDoktoraHeaderLewy">Pacjent</th><th class="tabelaDoktoraSrodek">Rodzaj badania</th><th class="tabelaDoktoraSrodek">Data badania</th><th class="tabelaDoktoraHeaderPrawy">Wynik</th></tr>';
                            while ($row = $result->fetch_assoc()) {
                                $sql2 = "SELECT CONCAT(imie, ' ', nazwisko) AS pelne_imie FROM pacjenci WHERE id_pacjenta = " . $row['id_pacjenta'];
                                $result2 = mysqli_query($con, $sql2);
                                $pelne_imie;
                                if ($result2) {
                                    if (mysqli_num_rows($result2) > 0) {
                                        while ($row2 = mysqli_fetch_assoc($result2)) {
                                            $pelne_imie = $row2['pelne_imie'];
                                        }
                                    } else {
                                        $pelne_imie = "Brak danych pacjenta!";
                                    }
                                } else {
                                    $pelne_imie = "Błąd pozyskiwania danych!";
                                }
                                echo "<tr>";
                                echo "<td class='poleTabeliDoktora'>" . $pelne_imie . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["rodzaj_badania"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["data_badania"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["wynik"] . "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            echo '</tbody>';
                            echo '</table>';
                        }
                        ?>
                    </div>
                </div>
                <div id="doktorSpacja3"></div>
                <!-- Recepty -->
                <div class="statyItem" style="background-color:#00a354;">
                    <div class="statyItemText">
                        <?php

                        $sql = "SELECT COUNT(*) AS liczba_recept FROM recepty";
                        $result = mysqli_query($con, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<h2>" . $row['liczba_recept'] . "</h2>";
                        } else {
                            echo "<h2>0</h2>";
                        }

                        ?>
                        <p style="margin-left:-110px;">Recepty</p>
                    </div>
                    <div class="statyItemIcon">
                        <span class="material-symbols-outlined">prescriptions</span>
                    </div>
                    <div class="statyItemDol" style="background-color:#01783e;">
                        <button onclick="ukryjSchowajRecepty()" id="btnRecepty"><span>Dowiedz się
                                więcej</span>&nbsp;<span
                                class="material-symbols-outlined">expand_circle_down</span></button>
                    </div>
                </div>
                <!-- Recepty - rozszerzenie -->
                <div class="tabelaStatyHolder" id="tabelaStatyHolderRecepty" style="display:none;">
                    <div class="tabelaPodStaty" id="tabelaPodStatyRecepty">
                        <?php
                        $sql = "SELECT * FROM recepty";
                        $result = mysqli_query($con, $sql);

                        // Wyświetlenie wyników na stronie
                        if ($result->num_rows > 0) {
                            echo '<table class="tabelaDoktora">';
                            echo '<tbody>';
                            echo '<tr><th class="tabelaDoktoraHeaderLewy">Pacjent</th><th class="tabelaDoktoraSrodek">Nazwa leku</th><th class="tabelaDoktoraSrodek">Ilość opakowań</th><th class="tabelaDoktoraSrodek">Data ważności recepty</th><th class="tabelaDoktoraHeaderPrawy">Data wystawienia recepty</th></tr>';
                            while ($row = $result->fetch_assoc()) {
                                $sql2 = "SELECT CONCAT(imie, ' ', nazwisko) AS pelne_imie FROM pacjenci WHERE id_pacjenta = " . $row['id_pacjenta'];
                                $result2 = mysqli_query($con, $sql2);
                                $pelne_imie;
                                if ($result2) {
                                    if (mysqli_num_rows($result2) > 0) {
                                        while ($row2 = mysqli_fetch_assoc($result2)) {
                                            $pelne_imie = $row2['pelne_imie'];
                                        }
                                    } else {
                                        $pelne_imie = "Brak danych pacjenta!";
                                    }
                                } else {
                                    $pelne_imie = "Błąd pozyskiwania danych!";
                                }
                                echo "<tr>";
                                echo "<td class='poleTabeliDoktora'>" . $pelne_imie . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["nazwa_leku"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["ilosc_opakowan"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["data_waznosci"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["data_wystawienia"] . "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            echo '</tbody>';
                            echo '</table>';
                        }
                        ?>
                    </div>
                </div>
                <div id="doktorSpacja4"></div>
                <!-- Choroby -->
                <div class="statyItem" style="background-color:#ab31f7;">
                    <div class="statyItemText">
                        <?php
                        $sql = "SELECT COUNT(*) AS liczba_chorob FROM historia_chorob";
                        $result = mysqli_query($con, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<h2>" . $row['liczba_chorob'] . "</h2>";
                        } else {
                            echo "<h2>0</h2>";
                        }

                        ?>
                        <p style="margin-left:-50px;">Historie chorób</p>
                    </div>
                    <div class="statyItemIcon">
                        <span class="material-symbols-outlined">coronavirus</span>
                    </div>
                    <div class="statyItemDol" style="background-color:#7d1cba;">
                        <button onclick="ukryjSchowajChoroby()" id="btnChoroby"><span>Dowiedz się
                                więcej</span>&nbsp;<span
                                class="material-symbols-outlined">expand_circle_down</span></button>
                    </div>
                </div>
                <!-- Choroby z rozszerzeniem -->
                <div class="tabelaStatyHolder" id="tabelaStatyHolderChoroby" style="display:none;">
                    <div class="tabelaPodStaty" id="tabelaPodStatyChoroby">
                        <?php
                        $sql = "SELECT * FROM historia_chorob";
                        $result = mysqli_query($con, $sql);

                        // Wyświetlenie wyników na stronie
                        if ($result->num_rows > 0) {
                            echo '<table class="tabelaDoktora">';
                            echo '<tbody>';
                            echo '<tr><th class="tabelaDoktoraHeaderLewy">Pacjent</th><th class="tabelaDoktoraSrodek">Nazwa choroby</th><th class="tabelaDoktoraSrodek">Data rozpoczęcia</th><th class="tabelaDoktoraSrodek">Data zakończenia</th><th class="tabelaDoktoraHeaderPrawy">Opis</th></tr>';
                            while ($row = $result->fetch_assoc()) {
                                $sql2 = "SELECT CONCAT(imie, ' ', nazwisko) AS pelne_imie FROM pacjenci WHERE id_pacjenta = " . $row['id_pacjenta'];
                                $result2 = mysqli_query($con, $sql2);
                                $pelne_imie;
                                if ($result2) {
                                    if (mysqli_num_rows($result2) > 0) {
                                        while ($row2 = mysqli_fetch_assoc($result2)) {
                                            $pelne_imie = $row2['pelne_imie'];
                                        }
                                    } else {
                                        $pelne_imie = "Brak danych pacjenta!";
                                    }
                                } else {
                                    $pelne_imie = "Błąd pozyskiwania danych!";
                                }
                                echo "<tr>";
                                echo "<td class='poleTabeliDoktora'>" . $pelne_imie . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["nazwa_choroby"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["data_rozpoczecia"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["data_zakonczenia"] . "</td>";
                                echo "<td class='poleTabeliDoktora'>" . $row["opis_choroby"] . "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            echo '</tbody>';
                            echo '</table>';
                        }
                        ?>
                    </div>
                </div>
            </div>


            <!-- Panel wyszukiwania pacjenta -->
            <div id="wyszukiwaniePacjenta">
                <div id="pacjentSearchBarHolder">
                    <input type="text" id="pacjentSearchBar" placeholder="Wpisz imię pacjenta lub PESEL">
                    <div id="pacjentSearchBarIcon">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                </div>
                
                <div id="wynikiSearchBar">
                </div>
            </div>


        </div>
    </div>
    <!-- Modal wyświetlany gdy klikniemy "Edytuj" przy wyszukiwaniu pacjenta. Wypełniany przez pacjentSearch.php -->
    <div id="modalNaPacjenta">
        <div id="obiektPacjentHolder">
            <div>
                <div id="modalPacjentImie">

                </div>
                <div id="modalUsunPacjenta">

                </div>
            </div>
            
            <div id="modalPacjentWizyty">
                
            </div>
            <div id="modalPacjentBadania">
            </div>
            <div id="modalPacjentChoroby">
            </div>
            <div id="modalPacjentRecepty">
            </div>
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


    <!--    OKNO DODAWANIA UŻYTKOWNIKA     -->
    <div id="dodajUzytkownika" class="modal">
        <form class="modal-content animate" action="/php/adduser.php" method="post">
            <div class="imgcontainer">
                <span onclick="document.getElementById('dodajUzytkownika').style.display='none';" class="close"
                    title="Close Modal"><span class="material-symbols-outlined"
                        style="font-weight:800;font-size:32px;">close</span></span>
                <span class="material-symbols-outlined" style="font-weight:200;font-size:100px;">person_add</span>
            </div>

            <div class="container">
                <div style="text-align:center;font-size:20px;font-weight:600;">Dodaj użytkownika</div>
                <label for="imie"><b>Imię</b></label>
                <input type="text" placeholder="Wpisz imię" name="imie" required>

                <label for="nazwisko"><b>Nazwisko</b></label>
                <input type="text" placeholder="Wpisz nazwisko" name="nazwisko" required>

                <label for="pesel"><b>Pesel</b></label>
                <!-- Dwa przyciski. Ten u góry korzysta z funkcji javascript która sprawdza czy wprowadzony pesel ma dlugosc 11 znakow. To drugie z tego nie korzysta i sprawia, ze wyskakuje okienko z bledem. -->
                <!--<input type="number" maxlength="11" placeholder="Wpisz pesel" name="pesel" id="pesel" required oninput="checkPeselLength()">  -->
                <input type="number" maxlength="11" placeholder="Wpisz pesel" name="pesel" id="pesel">
                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Wpisz email" name="email" required>
                <label for="imie"><b>Hasło</b></label>
                <input type="password" placeholder="Wpisz hasło" name="haslo" required>
                <div id="modalBtnHolder">
                    <!-- Jesli wybralismy wersje z javascript, to zamien blueButton na grayButton-->
                    <button type="submit" class="blueButton" id="btnDodajUsera">Dodaj</button>
                </div>
            </div>

            <div class="container" style="background-color:#f1f1f1">
                <div id="errorDisplay">
                    <?php if (isset($_SESSION['error'])) {
                        echo "Błąd! " . $_SESSION['error'];
                        unset($_SESSION['error']);
                    } ?>
                </div>
            </div>
        </form>
    </div>

    <?php
    // Jeśli przy dodawaniu użytkownika był błąd (na przykład przy niepoprawnej długości peselu, to wyrzuć błąd. Jeśli dodano poprawnie to powiadom o tym doktora)
    if (isset($_SESSION['addUserError'])) {

        // Wyświetl wiadomość błędu
        echo '<div id="errorNotification">
        <span class="material-symbols-outlined">warning</span>
        <button id="closeErrorNotification" onclick="closeErrorBox()"><span class="material-symbols-outlined">close</span></button>
        <div>
            <p>' . $_SESSION["addUserError"] . '</p>
        </div>
    </div>';
        unset($_SESSION['addUserError']);
    }
    if (isset($_SESSION['notification'])) {
        echo '<div id="goodNotification">
        <span class="material-symbols-outlined">check</span>
        <button id="closeGoodNotification" onclick="closeNotificationBox()"><span class="material-symbols-outlined">close</span></button>
        <div>
            <p>' . $_SESSION["notification"] . '</p>
        </div>
        </div>';
        unset($_SESSION['notification']);
    }
    ?>

    <div class="veryBottom">Projekt wykonany przez: Kamil Kucharski 313109</div>
</body>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>


</html>