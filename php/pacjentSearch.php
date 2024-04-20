<?php
session_start();
include('connection.php');
// Otrzymujemy to co wpisalismy do searchBar'a
$searchQuery = trim($_POST['query']);
// Sprawdzenie, czy wpisano liczbę
if (is_numeric($searchQuery)) 
{
    // Wyszukiwanie po peselu (jeśli tylko pesel został wpisany)
    $sql = "SELECT * FROM pacjenci WHERE pesel LIKE '%$searchQuery%'";
} 
else 
{
    // Wyszukiwanie po imieniu, nazwisku i peselu

    //Dzielę na części
    $keywords = explode(" ", $searchQuery);
    $conditions = array();

    //Dla każdej części z osobna przeszukuję tabelę
    foreach ($keywords as $keyword) 
    {
        $keyword = trim($keyword);// Pozbywam się białch spacji czy innych znaków zakończenia linii
        if ($keyword !== '') 
        {
            $conditions[] = "(imie LIKE '%$keyword%' OR nazwisko LIKE '%$keyword%' OR pesel LIKE '%$keyword%')";
        }
    }
    //Jeśli mamy same spacje czy inne tabulatory to wtedy wyszukaj wszyskich pacjentów, w przeciwnym razie połącz wszystkie wymagania wpisane przez nas i wyszukaj pacjentów 
    if (empty($conditions)) 
    {
        $sql = "SELECT * FROM pacjenci";
    } else {
        $sql = "SELECT * FROM pacjenci WHERE " . implode(" AND ", $conditions);
    }
}

// Wykonaj zapytanie i wypisz rekordy które pasują
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo '<table id="tabelaSearchPacjent">';
    echo '<tr><th>Imię i nazwisko</th><th>Pesel</th><th>Edytuj</th></tr>';

    // Wyświetl wyniki wyszukiwania
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
        echo '<td>' . $row['pesel'] . '</td>';
        echo '<td><button class="edit-button" data-id="' . $row['id_pacjenta'] . '" data-target="obiektPacjentHolder">Edytuj</button></td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'Brak wyników';
}
?>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    //Funkcje potrzebne do określenia "wzorca" wypisywanych dat lub godzin w tabeli podczas dodawania pacjenta
    function przykladDatyWlaczony(element) 
    {
        if (element.textContent === 'RRRR-MM-DD') 
        {
            element.textContent = '';
            element.classList.add('blackText');
        }
    }
    function przykladDatyWylaczony(element) 
    {
        if (element.textContent === '') 
        {
            element.textContent = 'RRRR-MM-DD';
            element.classList.remove('blackText');
        }
    }
    function przykladGodzinyIDatyWlaczony(element) 
    {
        if (element.textContent === 'RRRR-MM-DD GG:MM:SS')
        {
            element.textContent = '';
            element.classList.add('blackText');
        }
    }
    function przykladGodzinyIDatyWylaczony(element) 
    {
        if (element.textContent === '') 
        {
            element.textContent = 'RRRR-MM-DD GG:MM:SS';
            element.classList.remove('blackText');
        }
    }
    function przykladGodzinyWlaczony(element) 
    {
        if (element.textContent === 'GG:MM:SS') 
        {
            element.textContent = '';
            element.classList.add('blackText');
        }
    }
    function przykladGodzinyWylaczony(element) 
    {
        if (element.textContent === '') 
        {
            element.textContent = 'GG:MM:SS';
            element.classList.remove('blackText');
        }
    }
    //Po kliknięciu "edytuj" przy pacjencie go edytujemy
    $(document).ready(function () 
    {
        $('.edit-button').click(function () 
        {
            console.log("XD");
            var userId = $(this).data('id');
            $.ajax({
                url: '../php/save_session.php',
                type: 'POST',
                data: { userId: userId },
                success: function (response) {
                    // Pobranie wartości zmiennej sesji z odpowiedzi AJAX
                    var sessionId = response;
                    console.log('ID użytkownika zapisane w sesji. ID:', sessionId);
                    document.getElementById("modalNaPacjenta").style.display = 'block';
                    $.ajax({
                        url: '../php/get_name.php',
                        type: 'POST',
                        data: { id_pacjenta: sessionId },
                        success: function (response) {
                            console.log('ID pacjenta otrzymane');
                            // Przypisanie danych do elementu modalPacjentImie
                            $('#modalPacjentImie').text(response);
                            var deleteButton = $('<button></button>')
                                .attr('id', 'deleteButton')
                                .text('Usuń pacjenta');
                            deleteButton.click(function () {
                                $.ajax({
                                    url: '../php/delete_patient.php',
                                    type: 'POST',
                                    data: { id_pacjenta: sessionId },
                                    success: function (response) {
                                        console.log('Pacjent został usunięty wraz z wszelkimi jego rekordami');
                                        document.getElementById("modalNaPacjenta").style.display = 'none';
                                        // Tutaj możesz dodać dodatkowe akcje po usunięciu pacjenta
                                    },
                                    error: function (xhr, status, error) {
                                        console.log('Błąd podczas usuwania pacjenta: ' + error);
                                    }
                                });
                            });
                            $('#modalUsunPacjenta').html(deleteButton);

                        },
                        error: function (xhr, status, error) {
                            console.log('Błąd podczas pobierania danych pacjenta: ' + error);
                        }
                    });
                    function getWizytyFunction() {
                        $.ajax({
                            url: '../php/get_wizyty.php',
                            type: 'POST',
                            data: { id_pacjenta: sessionId },
                            success: function (response) {
                                console.log('Wyniki zapytania SELECT dla tabeli "wizyty" otrzymane');
                                // Sprawdzenie, czy odpowiedź nie jest pusta
                                if (response.length > 0) {
                                    var wizyty = JSON.parse(response);
                                    var table = $('<table class="modalTabelaPacjentaWizyty"></table>');
                                    var thead = $('<thead></thead>');
                                    var headerRow = $('<tr></tr>');
                                    headerRow.append('<th>Data wizyty</th>');
                                    headerRow.append('<th>Czas wizyty</th>');
                                    headerRow.append('<th>Opis</th>');
                                    headerRow.append('<th>Status</th>');
                                    headerRow.append('<th>Edycja</th>');
                                    thead.append(headerRow);
                                    table.append(thead);
                                    $.each(wizyty, function (index, value) {
                                        var row = $('<tr></tr>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaWizyty">' + value.data_wizyty + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaWizyty">' + value.czas_wizyty + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaWizyty">' + value.opis + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaWizyty">' + value.status + '</td>');
                                        var editButton = $('<button class="edit-button-tabelaModalPacjentWizyty">Edytuj</button>');
                                        editButton.data('id-wizyty', value.id_wizyty);
                                        editButton.data('id-pacjenta', value.id_pacjenta);
                                        editButton.click(function () {
                                            var row = $(this).closest('tr');
                                            var isEditing = row.data('editing');
                                            if (!isEditing) {
                                                row.data('editing', true);
                                                row.find('.modalPacjentaTabelaKomorkaWizyty:not(:last-child)').prop('contenteditable', true);
                                                $(this).text('Zapisz');
                                                $(this).removeClass('edit-button-tabelaModalPacjentWizyty').addClass('save-button-tabelaModalPacjentWizyty');
                                                var statusWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(4)').text();
                                                var selectHTML = '<select>' +
                                                    '<option value="oczekująca" ' + (statusWizyty == 'oczekująca' ? 'selected' : '') + '>oczekująca</option>' +
                                                    '<option value="odbyta" ' + (statusWizyty == 'odbyta' ? 'selected' : '') + '>odbyta</option>' +
                                                    '<option value="pacjent się nie pojawił" ' + (statusWizyty == 'pacjent się nie pojawił' ? 'selected' : '') + '>pacjent się nie pojawił</option>' +
                                                    '</select>';
                                                row.find('.modalPacjentaTabelaKomorkaWizyty').eq(-1).html(selectHTML);
                                            }
                                            else {
                                                // Edycja już w toku, wykonaj akcję zapisywania danych
                                                var idWizyty = $(this).data('id-wizyty');
                                                var idPacjenta = $(this).data('id-pacjenta');
                                                var dataWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(1)').text();
                                                var czasWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(2)').text();
                                                var opisWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(3)').text();
                                                var statusWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(4)').find('select').val();
                                                // Wysyłanie zaktualizowanych danych do bazy danych
                                                $.ajax({
                                                    url: '../php/update_wizyty.php',
                                                    type: 'POST',
                                                    data: {
                                                        id_wizyty: idWizyty,
                                                        id_pacjenta: idPacjenta,
                                                        data_wizyty: dataWizyty,
                                                        czas_wizyty: czasWizyty,
                                                        opis: opisWizyty,
                                                        status: statusWizyty
                                                    },
                                                    success: function (response) {
                                                        // Aktualizacja wiersza po zapisaniu danych
                                                        row.data('editing', false);
                                                        row.find('.modalPacjentaTabelaKomorkaWizyty:not(:last-child)').prop('contenteditable', false);
                                                        editButton.text('Edytuj');
                                                        editButton.removeClass('save-button-tabelaModalPacjentWizyty').addClass('edit-button-tabelaModalPacjentWizyty');
                                                        getWizytyFunction();
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.log('Błąd podczas zapisywania danych: ' + error);
                                                    }
                                                });
                                            }

                                        });
                                        row.append($('<td class="ModalPacjentaTabelaKomorkaWizyty"></td>').append(editButton));
                                        table.append(row);
                                    });

                                    // Dodanie pustego wiersza z przyciskiem "Dodaj"
                                    var emptyRow = $('<tr></tr>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaWizyty grayText" contenteditable="true" onblur="przykladGodzinyIDatyWylaczony(this)" onfocus="przykladGodzinyIDatyWlaczony(this)">RRRR-MM-DD GG:MM:SS</td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaWizyty grayText" contenteditable="true" onblur="przykladGodzinyWylaczony(this)" onfocus="przykladGodzinyWlaczony(this)">GG:MM:SS</td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaWizyty" contenteditable="true"></td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaWizyty" contenteditable="true"></td>');

                                    var select = $('<select class="modalPacjentaTabelaKomorkaWizytyDropdown"></select>');
                                    select.append('<option value="odbyta">odbyta</option>');
                                    select.append('<option value="oczekująca">oczekująca</option>');
                                    select.append('<option value="pacjent się nie pojawił">pacjent się nie pojawił</option>');
                                    emptyRow.find('.modalPacjentaTabelaKomorkaWizyty:last-child').append(select);


                                    var addButton = $('<button class="add-button-tabelaModalPacjentWizyty">Dodaj</button>');
                                    addButton.click(function () {
                                        var row = $(this).closest('tr');
                                        var dataWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(1)').text().trim();
                                        var czasWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(2)').text().trim();
                                        var opisWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(3)').text().trim();
                                        var statusWizyty = row.find('.modalPacjentaTabelaKomorkaWizyty:nth-child(4)').find('select').val();
                                        if (dataWizyty !== '' && czasWizyty !== '' && opisWizyty !== '' && statusWizyty !== '') {
                                            // Wysyłanie danych do bazy danych
                                            $.ajax({
                                                url: '../php/add_wizyty.php',
                                                type: 'POST',
                                                data: {
                                                    id_pacjenta: sessionId,
                                                    data_wizyty: dataWizyty,
                                                    czas_wizyty: czasWizyty,
                                                    opis: opisWizyty,
                                                    status: statusWizyty
                                                },
                                                success: function (response) {
                                                    // Przetwarzanie odpowiedzi, np. odświeżenie listy po dodaniu
                                                    console.log('Nowy wiersz został dodany do bazy danych');
                                                    getWizytyFunction();
                                                },
                                                error: function (xhr, status, error) {
                                                    console.log('Błąd podczas dodawania wiersza: ' + error);
                                                }
                                            });
                                        }
                                        else {
                                            console.log('Uzupełnij wszystkie pola');
                                        }
                                    });

                                    emptyRow.append($('<td class="ModalPacjentaTabelaKomorkaWizyty"></td>').append(addButton));
                                    table.append(emptyRow);

                                    $('#modalPacjentWizyty').empty().append(table);
                                } else {
                                    $('#modalPacjentWizyty').html('<p>Brak wizyt pacjenta!</p>');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Błąd podczas pobierania wizyt pacjenta: ' + error);
                            }
                        });
                    }
                    getWizytyFunction();


                    function getBadaniaFunction() {
                        $.ajax({
                            url: '../php/get_badania.php',
                            type: 'POST',
                            data: { id_pacjenta: sessionId },
                            success: function (response) {
                                console.log('Wyniki zapytania SELECT dla tabeli "badania" otrzymane');
                                // Sprawdzenie, czy odpowiedź nie jest pusta
                                if (response.length > 0) {
                                    var badania = JSON.parse(response);
                                    var table = $('<table class="modalTabelaPacjentaBadania"></table>');
                                    var thead = $('<thead></thead>');
                                    var headerRow = $('<tr></tr>');
                                    headerRow.append('<th>Data badania</th>');
                                    headerRow.append('<th>Rodzaj badania</th>');
                                    headerRow.append('<th>Wynik</th>');
                                    headerRow.append('<th>Edycja</th>');
                                    thead.append(headerRow);
                                    table.append(thead);

                                    $.each(badania, function (index, value) {
                                        var row = $('<tr></tr>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaBadania">' + value.data_badania + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaBadania">' + value.rodzaj_badania + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaBadania">' + value.wynik + '</td>');

                                        var editButton = $('<button class="edit-button-tabelaModalPacjentBadania">Edytuj</button>');
                                        editButton.data('id-badania', value.id_badania);
                                        editButton.data('id-pacjenta', value.id_pacjenta);
                                        editButton.click(function () {
                                            var row = $(this).closest('tr');
                                            var isEditing = row.data('editing');

                                            if (!isEditing) {
                                                row.data('editing', true);
                                                row.find('.modalPacjentaTabelaKomorkaBadania:not(:last-child)').prop('contenteditable', true);
                                                $(this).text('Zapisz');
                                                $(this).removeClass('edit-button-tabelaModalPacjentBadania').addClass('save-button-tabelaModalPacjentBadania');
                                            } else {
                                                // Edycja już w toku, wykonaj akcję zapisywania danych
                                                var idBadania = $(this).data('id-badania');
                                                var idPacjenta = $(this).data('id-pacjenta');
                                                var dataBadania = row.find('.modalPacjentaTabelaKomorkaBadania:nth-child(1)').text();
                                                var rodzajBadania = row.find('.modalPacjentaTabelaKomorkaBadania:nth-child(2)').text();
                                                var wynikBadania = row.find('.modalPacjentaTabelaKomorkaBadania:nth-child(3)').text();

                                                // Wysyłanie zaktualizowanych danych do bazy danych
                                                $.ajax({
                                                    url: '../php/update_badania.php',
                                                    type: 'POST',
                                                    data: {
                                                        id_badania: idBadania,
                                                        id_pacjenta: idPacjenta,
                                                        data_badania: dataBadania,
                                                        rodzaj_badania: rodzajBadania,
                                                        wynik: wynikBadania
                                                    },
                                                    success: function (response) {
                                                        // Aktualizacja wiersza po zapisaniu danych
                                                        row.data('editing', false);
                                                        row.find('.modalPacjentaTabelaKomorkaBadania:not(:last-child)').prop('contenteditable', false);
                                                        editButton.text('Edytuj');
                                                        editButton.removeClass('save-button-tabelaModalPacjentBadania').addClass('edit-button-tabelaModalPacjentBadania');
                                                        getBadaniaFunction();
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.log('Błąd podczas zapisywania danych: ' + error);
                                                    }
                                                });
                                            }
                                        });
                                        row.append($('<td class="ModalPacjentaTabelaKomorkaBadania"></td>').append(editButton));
                                        table.append(row);
                                    });

                                    // Dodanie pustego wiersza z przyciskiem "Dodaj"
                                    var emptyRow = $('<tr></tr>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaBadania grayText" contenteditable="true"  onblur="przykladDatyWylaczony(this)" onfocus="przykladDatyWlaczony(this)">RRRR-MM-DD</td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaBadania" contenteditable="true"></td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaBadania" contenteditable="true"></td>');

                                    var addButton = $('<button class="add-button-tabelaModalPacjentBadania">Dodaj</button>');
                                    addButton.click(function () {
                                        var row = $(this).closest('tr');
                                        var idPacjenta = $(this).data('id-pacjenta');
                                        var dataBadania = row.find('.modalPacjentaTabelaKomorkaBadania:nth-child(1)').text().trim();
                                        var rodzajBadania = row.find('.modalPacjentaTabelaKomorkaBadania:nth-child(2)').text().trim();
                                        var wynikBadania = row.find('.modalPacjentaTabelaKomorkaBadania:nth-child(3)').text().trim();
                                        if (dataBadania !== '' && rodzajBadania !== '' && wynikBadania !== '') {
                                            // Wysyłanie danych do bazy danych
                                            $.ajax({
                                                url: '../php/add_badania.php',
                                                type: 'POST',
                                                data:
                                                {
                                                    id_pacjenta: sessionId,
                                                    data_badania: dataBadania,
                                                    rodzaj_badania: rodzajBadania,
                                                    wynik: wynikBadania
                                                },
                                                success: function (response) {
                                                    // Przetwarzanie odpowiedzi, np. odświeżenie listy po dodaniu
                                                    console.log('Nowy wiersz został dodany do bazy danych');
                                                    getBadaniaFunction();
                                                },
                                                error: function (xhr, status, error) {
                                                    console.log('Błąd podczas dodawania wiersza: ' + error);
                                                }
                                            });
                                        }
                                        else {
                                            console.log('Uzupełnij wszystkie pola');
                                        }
                                    });

                                    emptyRow.append($('<td class="ModalPacjentaTabelaKomorkaBadania"></td>').append(addButton));
                                    table.append(emptyRow);

                                    $('#modalPacjentBadania').empty().append(table);
                                } else {
                                    $('#modalPacjentBadania').html('<p>Brak badań pacjenta!</p>');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Błąd podczas pobierania badań pacjenta: ' + error);
                            }
                        });
                    }
                    getBadaniaFunction();

                    function getChorobyFunction() {
                        $.ajax({
                            url: '../php/get_choroby.php',
                            type: 'POST',
                            data: { id_pacjenta: sessionId },
                            success: function (response) {
                                console.log('Wyniki zapytania SELECT dla tabeli "choroby" otrzymane');
                                // Sprawdzenie, czy odpowiedź nie jest pusta
                                if (response.length > 0) {
                                    var choroby = JSON.parse(response);
                                    var table = $('<table class="modalTabelaPacjentaChoroby"></table>');
                                    table.append('<tr><th>Nazwa choroby</th><th>Data rozpoczęcia</th><th>Data zakończenia</th><th>Opis choroby</th><th>Edycja</th></tr>');

                                    $.each(choroby, function (index, value) {
                                        var row = $('<tr></tr>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaChoroby">' + value.nazwa_choroby + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaChoroby">' + value.data_rozpoczecia + '</td>');
                                        if (value.data_zakonczenia == null) {
                                            row.append('<td class="modalPacjentaTabelaKomorkaChoroby">Choroba trwa</td>');
                                        }
                                        else {
                                            row.append('<td class="modalPacjentaTabelaKomorkaChoroby">' + value.data_zakonczenia + '</td>');
                                        }

                                        row.append('<td class="modalPacjentaTabelaKomorkaChoroby">' + value.opis_choroby + '</td>');

                                        var editButton = $('<button class="edit-button-tabelaModalPacjentChoroby">Edytuj</button>');
                                        editButton.data('id-historii', value.id_historii);
                                        editButton.data('id-pacjenta', value.id_pacjenta);
                                        editButton.click(function () {
                                            var row = $(this).closest('tr');
                                            var isEditing = row.data('editing');

                                            if (!isEditing) {
                                                row.data('editing', true);
                                                row.find('.modalPacjentaTabelaKomorkaChoroby:not(:last-child)').prop('contenteditable', true);
                                                $(this).text('Zapisz');
                                                $(this).removeClass('edit-button-tabelaModalPacjentChoroby').addClass('save-button-tabelaModalPacjentChoroby');
                                            }
                                            else {
                                                // Edycja już w toku, wykonaj akcję zapisywania danych
                                                var idHistorii = $(this).data('id-historii');
                                                var idPacjenta = $(this).data('id-pacjenta');
                                                var nazwaChoroby = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(1)').text();
                                                var dataRozpoczecia = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(2)').text();
                                                var dataZakonczenia = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(3)').text();
                                                var opisChoroby = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(4)').text();
                                                // Wysyłanie zaktualizowanych danych do bazy danych
                                                $.ajax({
                                                    url: '../php/update_choroby.php',
                                                    type: 'POST',
                                                    data: {
                                                        id_historii: idHistorii,
                                                        id_pacjenta: idPacjenta,
                                                        nazwa_choroby: nazwaChoroby,
                                                        data_rozpoczecia: dataRozpoczecia,
                                                        data_zakonczenia: dataZakonczenia,
                                                        opis_choroby: opisChoroby
                                                    },
                                                    success: function (response) {
                                                        // Aktualizacja wiersza po zapisaniu danych
                                                        row.data('editing', false);
                                                        row.find('.modalPacjentaTabelaKomorkaChoroby:not(:last-child)').prop('contenteditable', false);
                                                        editButton.text('Edytuj');
                                                        editButton.removeClass('save-button-tabelaModalPacjentChoroby').addClass('edit-button-tabelaModalPacjentChoroby');
                                                        getChorobyFunction();
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.log('Błąd podczas zapisywania danych: ' + error);
                                                    }
                                                });
                                            }
                                        });

                                        row.append($('<td class="ModalPacjentaTabelaKomorkaChoroby"></td>').append(editButton));
                                        table.append(row);
                                    });

                                    // Dodanie pustego wiersza z przyciskiem "Dodaj"
                                    var emptyRow = $('<tr></tr>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaChoroby" contenteditable="true"></td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaChoroby grayText" contenteditable="true" onblur="przykladDatyWylaczony(this)" onfocus="przykladDatyWlaczony(this)">RRRR-MM-DD</td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaChoroby grayText" contenteditable="true" onblur="przykladDatyWylaczony(this)" onfocus="przykladDatyWlaczony(this)">RRRR-MM-DD</td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaChoroby" contenteditable="true"></td>');

                                    var addButton = $('<button class="add-button-tabelaModalPacjentChoroby">Dodaj</button>');
                                    addButton.click(function () {
                                        var row = $(this).closest('tr');
                                        var nazwaChoroby = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(1)').text();
                                        var dataRozpoczecia = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(2)').text();
                                        var dataZakonczenia = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(3)').text();
                                        var opisChoroby = row.find('.modalPacjentaTabelaKomorkaChoroby:nth-child(4)').text();
                                        if (dataZakonczenia === "RRRR-MM-DD") {
                                            dataZakonczenia = '';
                                        }
                                        console.log(sessionId + " " + nazwaChoroby + " " + dataRozpoczecia + " " + dataZakonczenia + " " + opisChoroby);
                                        // Wysyłanie danych do bazy danych
                                        $.ajax({
                                            url: '../php/add_choroby.php',
                                            type: 'POST',
                                            data: {
                                                id_pacjenta: sessionId,
                                                nazwa_choroby: nazwaChoroby,
                                                data_rozpoczecia: dataRozpoczecia,
                                                data_zakonczenia: dataZakonczenia,
                                                opis_choroby: opisChoroby
                                            },
                                            success: function (response) {
                                                // Przetwarzanie odpowiedzi, np. odświeżenie listy po dodaniu
                                                console.log('Nowy wiersz został dodany do bazy danych');
                                                getChorobyFunction();
                                            },
                                            error: function (xhr, status, error) {
                                                console.log('Błąd podczas dodawania wiersza: ' + error);
                                            }
                                        });

                                    });

                                    emptyRow.append($('<td class="ModalPacjentaTabelaKomorkaChoroby"></td>').append(addButton));
                                    table.append(emptyRow);

                                    $('#modalPacjentChoroby').empty().append(table);
                                } else {
                                    $('#modalPacjentChoroby').html('<p>Brak chorób pacjenta!</p>');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Błąd podczas pobierania chorób pacjenta: ' + error);
                            }
                        });
                    }
                    getChorobyFunction();


                    function getReceptyFunction() {
                        $.ajax({
                            url: '../php/get_recepty.php',
                            type: 'POST',
                            data: { id_pacjenta: sessionId },
                            success: function (response) {
                                console.log('Wyniki zapytania SELECT dla tabeli "recepty" otrzymane');
                                // Sprawdzenie, czy odpowiedź nie jest pusta
                                if (response.length > 0) {
                                    var recepty = JSON.parse(response);
                                    var table = $('<table class="modalTabelaPacjentaRecepty"></table>');
                                    table.append('<tr><th>Nazwa leku</th><th>Ilość opakowań</th><th>Data zakończenia recepty</th><th>Edycja</th></tr>');

                                    $.each(recepty, function (index, value) {
                                        var row = $('<tr></tr>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaRecepty">' + value.nazwa_leku + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaRecepty">' + value.ilosc_opakowan + '</td>');
                                        row.append('<td class="modalPacjentaTabelaKomorkaRecepty">' + value.data_waznosci + '</td>');

                                        var editButton = $('<button class="edit-button-tabelaModalPacjentRecepty">Edytuj</button>');
                                        editButton.data('id-recepty', value.id_recepty);
                                        editButton.data('id-pacjenta', value.id_pacjenta);
                                        editButton.click(function () {
                                            var row = $(this).closest('tr');
                                            var isEditing = row.data('editing');

                                            if (!isEditing) {
                                                row.data('editing', true);
                                                row.find('.modalPacjentaTabelaKomorkaRecepty:not(:last-child)').prop('contenteditable', true);
                                                $(this).text('Zapisz');
                                                $(this).removeClass('edit-button-tabelaModalPacjentRecepty').addClass('save-button-tabelaModalPacjentRecepty');
                                            } else {
                                                // Edycja już w toku, wykonaj akcję zapisywania danych
                                                var idRecepty = $(this).data('id-recepty');
                                                var idPacjenta = $(this).data('id-pacjenta');
                                                var nazwaLeku = row.find('.modalPacjentaTabelaKomorkaRecepty:nth-child(1)').text().trim();
                                                var iloscOpakowan = row.find('.modalPacjentaTabelaKomorkaRecepty:nth-child(2)').text().trim();
                                                var dataWaznosci = row.find('.modalPacjentaTabelaKomorkaRecepty:nth-child(3)').text().trim();

                                                // Wysyłanie zaktualizowanych danych do bazy danych
                                                $.ajax({
                                                    url: '../php/update_recepty.php',
                                                    type: 'POST',
                                                    data: {
                                                        id_recepty: idRecepty,
                                                        id_pacjenta: idPacjenta,
                                                        nazwa_leku: nazwaLeku,
                                                        ilosc_opakowan: iloscOpakowan,
                                                        data_waznosci: dataWaznosci
                                                    },
                                                    success: function (response) {
                                                        // Aktualizacja wiersza po zaktualizowaniu danych
                                                        row.data('editing', false);
                                                        row.find('.modalPacjentaTabelaKomorkaRecepty:not(:last-child)').prop('contenteditable', false);
                                                        editButton.text('Edytuj');
                                                        editButton.removeClass('save-button-tabelaModalPacjentRecepty').addClass('edit-button-tabelaModalPacjentRecepty');
                                                        getReceptyFunction();
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.log('Błąd podczas zapisywania danych: ' + error);
                                                    }
                                                });
                                            }
                                        });

                                        row.append($('<td class="ModalPacjentaTabelaKomorkaRecepty"></td>').append(editButton));
                                        table.append(row);
                                    });

                                    // Dodanie pustego wiersza z przyciskiem "Dodaj"
                                    var emptyRow = $('<tr></tr>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaRecepty" contenteditable="true"></td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaRecepty" contenteditable="true"></td>');
                                    emptyRow.append('<td class="modalPacjentaTabelaKomorkaRecepty grayText" contenteditable="true" onblur="przykladDatyWylaczony(this)" onfocus="przykladDatyWlaczony(this)">RRRR-MM-DD</td>');

                                    var addButton = $('<button class="add-button-tabelaModalPacjentRecepty">Dodaj</button>');
                                    addButton.click(function () {
                                        var row = $(this).closest('tr');
                                        var nazwaLeku = row.find('.modalPacjentaTabelaKomorkaRecepty:nth-child(1)').text().trim();
                                        var iloscOpakowan = row.find('.modalPacjentaTabelaKomorkaRecepty:nth-child(2)').text().trim();
                                        var dataWaznosci = row.find('.modalPacjentaTabelaKomorkaRecepty:nth-child(3)').text().trim();

                                        if (nazwaLeku !== '' && iloscOpakowan !== '' && dataWaznosci !== '') {
                                            // Wysyłanie danych do bazy danych
                                            $.ajax({
                                                url: '../php/add_recepty.php',
                                                type: 'POST',
                                                data: {
                                                    id_pacjenta: sessionId,
                                                    nazwa_leku: nazwaLeku,
                                                    ilosc_opakowan: iloscOpakowan,
                                                    data_waznosci: dataWaznosci
                                                },
                                                success: function (response) {
                                                    // Odświeżenie listy po dodaniu
                                                    console.log('Nowy wiersz został dodany do bazy danych');
                                                    getReceptyFunction();
                                                },
                                                error: function (xhr, status, error) {
                                                    console.log('Błąd podczas dodawania wiersza: ' + error);
                                                }
                                            });
                                        }
                                        else {
                                            console.log('Uzupełnij wszystkie pola');
                                        }
                                    });

                                    emptyRow.append($('<td class="ModalPacjentaTabelaKomorkaRecepty"></td>').append(addButton));
                                    table.append(emptyRow);

                                    $('#modalPacjentRecepty').empty().append(table);
                                } else {
                                    $('#modalPacjentRecepty').html('<p>Brak wizyt pacjenta!</p>');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Błąd podczas pobierania wizyt pacjenta: ' + error);
                            }
                        });
                    }

                    getReceptyFunction();


                },
                error: function (xhr, status, error) {
                    console.log('Błąd podczas zapisywania ID użytkownika w sesji: ' + error);
                }
            });
        });
    });

</script>