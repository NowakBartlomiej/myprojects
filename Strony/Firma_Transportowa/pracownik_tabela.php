<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tabela Pracowników</title>
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
    
    
    // Zakodowanie hasel uzytkownikow - przy uzyciu zalecanych funkci PHP
    //print("<p>anowak: ".password_hash("anowak", PASSWORD_DEFAULT)."</p>");
    
    //print("<p>zlipinska: ".password_hash("zlipinska", PASSWORD_DEFAULT)."</p>");
    
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
        //print("<p class='msg success'>Połączenie z serwerem baz danych $serwer OK.</p>");
        
        // IdPracownik, Imie, Nazwisko, Stanowisko, NrTelefonu, AdresEmail

        // TABELA PRACOWNIKOW
        print("<h2>Pracownicy</h2>");
        print("<table>
                <thead>
                    <tr>
                        <td>Identyfikator</td>
                        <td>Imię</td>
                        <td>Nazwisko</td>
                        <td>Stanowisko</td>
                        <td>Numer telefonu</td>
                        <td>Adres e-mail</td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>");
        

        // WIERSZE TABELI

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT IdPracownik, Imie, Nazwisko, Stanowisko, NrTelefonu, AdresEmail
        FROM dbo.Pracownik
        ORDER BY Nazwisko ASC, Imie ASC;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) {
            print("<tr>
                    <td colspan='8'>Brak danych pracowników w bazie</td>
                </tr>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC)) {
                $IdPracownik = $wiersz["IdPracownik"];
                $Imie = $wiersz["Imie"];
                $Nazwisko = $wiersz["Nazwisko"];
                $Stanowisko = $wiersz["Stanowisko"];
                $NrTelefonu = $wiersz["NrTelefonu"];
                $AdresEmail = $wiersz["AdresEmail"];

                print("<tr>
                        <td>$IdPracownik</td>
                        <td>$Imie</td>
                        <td>$Nazwisko</td>
                        <td>$Stanowisko</td>
                        <td>$NrTelefonu</td>
                        <td>$AdresEmail</td>
                        <td><a class='btn upd' href='pracownik_edytuj.php?IdPracownik=$IdPracownik'>Edytuj</a></td>
                        <td><a class='btn del' href='pracownik_usun.php?IdPracownik=$IdPracownik'>Usuń</a></td>
                    </tr>");
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

        } // Else Jezeli zostaly zwrocone wiersze

        print("</tbody>
            </table>");

        // Zamkniecie polaczenia
        sqlsrv_close($polaczenie);
    
        // FORMULARZ
        print("
        <h2>Nowy pracownik</h2>
        <form id='formularzPracownikDodaj' method='get' action='pracownik_dodaj.php'>
            <fieldset>
                <legend>Parametry tabeli</legend>
                <p class='lbl'>
                    <label for='IdPracownik'>Identyfikator*</label>
                    <input type='text' name='IdPracownik' id='IdPracownik' maxlength='10'>
                </p>
    
                <p class='lbl'>
                    <label for='Imie'>Imię*</label>
                    <input type='text' name='Imie' id='Imie' maxlength='30'>
                </p>
    
                <p class='lbl'>
                    <label for='Nazwisko'>Nazwisko*</label>
                    <input type='text' name='Nazwisko' id='Nazwisko' maxlength='40'>
                </p>
    
                <p class='lbl'>
                    <label for='Stanowisko'>Stanowisko*</label>
                    <input type='text' name='Stanowisko' id='Stanowisko' maxlength='50'>
                </p>
    
                <p class='lbl'>
                    <label for='NrTelefonu'>Numer telefonu*</label>
                    <input type='text' name='NrTelefonu' id='NrTelefonu' maxlength='20'>
                </p>
                
                <p class='lbl'>
                    <label for='AdresEmail'>Adres Email*</label>
                    <input type='text' name='AdresEmail' id='AdresEmail' maxlength='50'>
                </p>
    
            </fieldset>
    
            <p>
                <input type='submit' value='Zapisz'>
                <input type='reset' value='Wyczyść pola'>
            </p>
    
        </form>");

        print("<p><br/>
            <a class='btn def' href='logowanie_koniec.php'>Wyloguj</a></p>");
    

    } // else Jezeli polaczenie zostalo nawiazane
}
?>

</body>

</html>