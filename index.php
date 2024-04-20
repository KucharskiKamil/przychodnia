<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.ico">
    <!-- Link do ikony na inforBarze-->
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
            session_start();
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                echo '<form method="post" action="php/logout.php">
                        <input type="hidden" name="logout" value="true">
                        <button type="submit" id="logoutBtn">
                            <span class="material-symbols-outlined">logout</span>Wyloguj
                        </button>
                    </form>';
            }
            if (!isset($_SESSION['logged_in'])) {

                echo '<button id="loginBtn" onclick="document.getElementById(\'id01\').style.display=\'block\'"><span class="material-symbols-outlined">login</span>Logowanie
                </button>';
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
            <?php
            if (isset($_SESSION['logged_in'])) {
                if ($_SESSION['is_doctor'] == true) {
                    echo '<a class="navLink" href="doktor.php">Panel doktora</a>';
                } else {
                    echo '<a class="navLink" href="pacjent.php">Panel pacjenta</a>';
                }
            }
            ?>
            <a class="navLink" href="#lastDiv">O nas</a>
            <a class="navLink" href="#lastDiv">Doktorzy</a>
            <a class="navLink" href="#lastDiv">Kontakt</a>
            <a href="javascript:void(0);" class="icon" onclick="showHideBar()"><span
                    class="material-symbols-outlined">menu</span></a>
        </span>
        <span id="spaceLR"></span>
    </div>
    <div id="hiddenMenuForNav">
        <?php
        if (isset($_SESSION['logged_in'])) {
            if ($_SESSION['is_doctor'] == true) {
                echo '<a class="hiddenLink" href="doktor.php">Panel doktora</a>';
            } else {
                echo '<a class="hiddenLink" href="pacjent.php">Panel pacjenta</a>';
            }
        }
        ?>
        <a class="hiddenLink">O nas</a>
        <a class="hiddenLink">Doktorzy</a>
        <a class="hiddenLink" href="index.php#dol">Kontakt</a>
    </div>
    <?php
    if (isset($_SESSION['logged_in']) && isset($_SESSION["is_doctor"])) {
        if (($_SESSION['is_doctor'] == 1)) {
            echo '<div class="whoIsLogged">Zalogowano jako Doktor ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</div>';
        }
        if ($_SESSION["is_doctor"] == 0) {
            echo '<div class="whoIsLogged">Zalogowano jako pacjent ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</div>';
        }
    }
    ?>
    <div id="mainBodyInfo">
        <div id="mBILeftSegment">
            <h1>Twoje zdrowie, nasza troska.</h1>
            <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis aliquam iure quia blanditiis nam
                molestias sequi praesentium. Corrupti nobis voluptatibus earum amet. Earum, quidem? At sapiente porro
                illo laborum optio.</span>
            <span id="mBI">
                <button id="mBIButton1">Umów się na wizytę</button>
            </span>
        </div>
        <div id="mBIRightSegment">
            <img alt="doctorImage" src="./images/doktorek.png">
        </div>
    </div>
    <div class="aboutUsDiv">
        <span id="spaceLR"></span>
        <div class="aULeft">
            <img alt="doctorPhoto2" src="./images/lekarka.jpg">
        </div>
        <div class="aURight">
            <span class="TextHolder">
                <h2 class="textMain">
                    Jesteśmy <span>MediHelp</span> - <br>twoja najlepsza klinika medyczna
                </h2>
                <p>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores harum doloremque delectus vel
                    eveniet odio necessitatibus sequi excepturi ut dolores, placeat modi tenetur illum autem dolorem,
                    qui dolor, dicta dolore.
                </p>
                <a href="#lastDiv"><button>Skontaktuj sie z nami</button></a>
            </span>

        </div>
        <span id="spaceLR"></span>
    </div>



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


    <!--    OKNO LOGOWANIA     -->
    <div id="id01" class="modal" style="display: <?php echo isset($_SESSION['error']) ? 'block' : 'none'; ?>">
        <form class="modal-content animate" action="/php/authentication.php" method="post">
            <div class="imgcontainer">
                <span onclick="document.getElementById('id01').style.display='none';" class="close"
                    title="Close Modal">&times;</span>
                <img src="images/img_avatar2.png" alt="Avatar" class="avatar">
            </div>

            <div class="container">
                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Wpisz Email" name="email" required>

                <label for="psw"><b>Hasło</b></label>
                <input type="password" placeholder="Wpisz hasło" name="psw" required>
                <div id="modalBtnHolder">
                    <button type="submit" id="modalLoginBtn">Zaloguj</button>
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