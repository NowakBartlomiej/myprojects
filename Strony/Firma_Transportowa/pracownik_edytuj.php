<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edytuj Dane Pracownika</title>
    <meta name="keywords" content="transport, firma, firma transportowa" />
    <meta name="description" content="Strona firmy transportowej w ramach projektu P1" />
    <meta name="author" content="Bartłomiej Nowak" />

    <link rel="Stylesheet" href="style_php.css" type="text/css" />
</head>

<body>

<?php
    if (isset($_SESSION["zalogowany"])
    && ($_SESSION["zalogowany"] == false)
    || (!isset($_SESSION["zalogowany"]))
    || (!isset($_SESSION["uzytkownik"]))) {

    print("<h2>Odmowa dostępu</h2>");

    

// Faktyczne zakonczenie sesji
    $_SESSION["zalogowany"] = false;

    if (isset($_SESSION["uzytkownik"])) {
        unset($_SESSION["uzytkownik"]);
    }

    if (isset($_SESSION["Imie"])) {
        unset($_SESSION["Imie"]);
    }

    if (isset($_SESSION["Nazwisko"])) {
        unset($_SESSION["Nazwisko"]);
    }

    session_destroy();

    print("<p class='msg error'>Ta funkcja dostępna jest tylko dla zalogowanych użytkowników</p>");

    die("<p><a class='btn def' href='logowanie_formularz.php'>Powrót do formularza logowania</a></p>");
    
} else if (($_SESSION["zalogowany"] == true) &&  (isset($_SESSION["uzytkownik"]))) {
    
    
    
    // Sprawdzenie czy w zadaniu HTTP zostal przekazany klucz wiersza
    if (!isset($_GET["IdPracownik"]) 
        || (trim($_GET["IdPracownik"]) == "")
        || !is_numeric($_GET["IdPracownik"])
        ) {
            print("<p class='msg error'>Nie można edytować danych pracownika, ponieważ nie został on wybrany</p>");

            die("<p><a class='btn def' href='pracownik_tabela.php'>Powrót do wykazu pracowników</a></p>");
        } // if jezeli dane sa niepoprawne
        // else Jezeli zostal przekazany klucz wiersza do edycji
        else {
    
    // LACZENIE SIE Z SERWEREM
    $serwer = "DESKTOP-DEQE0U7\SQLEXPRESS";

    $dane_polaczenia = array("Database" => "P31_P1", "CharacterSet" => "UTF-8");

    // Proba polaczenia z serwerem baz danych
    $polaczenie = sqlsrv_connect($serwer, $dane_polaczenia);

    // Jezeli proba polaczenia nie powiodla sie
    if ($polaczenie == false) {
        print("<p class='msg error'>Połączenie z serwerem baz danych $serwer nie powiodło się.</p>");
        die(print_r(sqlsrv_errors(), true));
    }
    // Jezeli polaczenie zostalo nawiazane
    else {
        // Pobranie klucza wiersza do edycji
        $IdPracownik = trim($_GET["IdPracownik"]);

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT IdPracownik, Imie, Nazwisko, Stanowisko, NrTelefonu, AdresEmail
        FROM dbo.Pracownik
        WHERE IdPracownik = $IdPracownik;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) {
            print("<p class='msg error'>W bazie nie ma zapisanych danych pracownika o identyfikatorze <strong>$IdPracownik</strong></p>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC)) {
                
                $Imie = $wiersz["Imie"];
                $Nazwisko = $wiersz["Nazwisko"];
                $Stanowisko = $wiersz["Stanowisko"];
                $NrTelefonu = $wiersz["NrTelefonu"];
                $AdresEmail = $wiersz["AdresEmail"];

                
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

        

        // Zamkniecie polaczenia
        sqlsrv_close($polaczenie);
    
        // FORMULARZ
        print("
        <h2>Edycja danych pracownika</h2>
        <form id='formularzPracownikEdytuj' method='get' action='pracownik_edytuj_potw.php'>
            <fieldset>
                <legend>Parametry tabeli</legend>
                <p class='lbl'>
                    <label for='IdPracownik'>Identyfikator*</label>
                    <input type='text' name='IdPracownik' id='IdPracownik' maxlength='10' value='$IdPracownik' readonly='readonly'>
                </p>
    
                <p class='lbl'>
                    <label for='Imie'>Imię*</label>
                    <input type='text' name='Imie' id='Imie' maxlength='30' value='$Imie'>
                </p>
    
                <p class='lbl'>
                    <label for='Nazwisko'>Nazwisko*</label>
                    <input type='text' name='Nazwisko' id='Nazwisko' maxlength='40' value='$Nazwisko'>
                </p>
    
                <p class='lbl'>
                    <label for='Stanowisko'>Stanowisko*</label>
                    <input type='text' name='Stanowisko' id='Stanowisko' maxlength='50' value='$Stanowisko'>
                </p>
    
                <p class='lbl'>
                    <label for='NrTelefonu'>Numer telefonu*</label>
                    <input type='text' name='NrTelefonu' id='NrTelefonu' maxlength='20' value='$NrTelefonu'>
                </p>
                
                <p class='lbl'>
                    <label for='AdresEmail'>Adres Email*</label>
                    <input type='text' name='AdresEmail' id='AdresEmail' maxlength='50' value='$AdresEmail'>
                </p>
    
            </fieldset>
    
            <p>
                <input type='submit' value='Zapisz'>
                <input type='reset' value='Przywróć poprzednie'>
                <a class='btn def' href='pracownik_tabela.php'>Anuluj</a>
            </p>
    
        </form>");

    } // Else Jezeli zostaly zwrocone dane pracownika
    

    } // else Jezeli polaczenie zostalo nawiazane
} // else Jezeli dane sa poprawne
}
?>

</body>

</html>

