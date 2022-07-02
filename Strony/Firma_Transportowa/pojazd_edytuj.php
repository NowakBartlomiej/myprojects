<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edytuj Pojazd</title>
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
    if (!isset($_GET["IdPojazd"]) 
        || (trim($_GET["IdPojazd"]) == "")
        || !is_numeric($_GET["IdPojazd"])
        ) {
            print("<p class='msg error'>Nie można edytować danych pojazdu, ponieważ nie został on wybrany</p>");

            die("<p><a class='btn def' href='pojazd_tabela.php'>Powrót do wykazu pracowników</a></p>");
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
        $IdPojazd = trim($_GET["IdPojazd"]);

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT IdPojazd, IdPracownik, Marka, Model, RokProdukcji, StatusPojazdu, Opis
        FROM dbo.Pojazd
        WHERE IdPojazd = $IdPojazd;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) {
            print("<p class='msg error'>W bazie nie ma zapisanych danych pojazdu o identyfikatorze <strong>$IdPojazd</strong></p>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC)) {
                
                // IdPracownik, Marka, Model, RokProdukcji, StatusPojazdu, Opis

                $Marka = $wiersz["Marka"];
                $Model = $wiersz["Model"];
                $RokProdukcji = $wiersz["RokProdukcji"];
                $StatusPojazdu = $wiersz["StatusPojazdu"];
                $Opis = $wiersz["Opis"];
                $IdPracownik_wyb = $wiersz["IdPracownik"];

                
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

        

        
    
        // FORMULARZ
        print("
        <h2>Edycja danych pojazdu i pracownika</h2>
        <form id='formularzPojazdEdytuj' method='get' action='pojazd_edytuj_potw.php'>
            <fieldset>
                <legend>Pojazd i pracownik</legend>
                <p class='lbl'>
                    <label for='IdPojazd'>Identyfikator*</label>
                    <input type='text' name='IdPojazd' id='IdPojazd' maxlength='10' value='$IdPojazd' readonly='readonly'>
                </p>
    
                <p class='lbl'>
                    <label for='Marka'>Marka*</label>
                    <input type='text' name='Marka' id='Marka' maxlength='50' value='$Marka'>
                </p>
    
                <p class='lbl'>
                    <label for='Model'>Model*</label>
                    <input type='text' name='Model' id='Model' maxlength='50' value='$Model'>
                </p>
    
                <p class='lbl'>
                    <label for='RokProdukcji'>Rok produkcji*</label>
                    <input type='text' name='RokProdukcji' id='RokProdukcji' maxlength='4' value='$RokProdukcji'>
                </p>
    
                <p class='lbl'>
                    <label for='StatusPojazdu'>Status pojazdu*</label>
                    <input type='text' name='StatusPojazdu' id='StatusPojazdu' maxlength='50' value='$StatusPojazdu'>
                </p>
                
                <p class='lbl'>
                    <label for='Opis'>Opis</label>
                    <input type='text' name='Opis' id='Opis' maxlength='200' value='$Opis'>
                </p>");
    
                // Pola dynamiczne
                print("
                <p class='lbl'>
                    <label for='IdPracownik'>Pracownik*</label>
                    <select size='1' name='IdPracownik' id='IdPracownik'>
                    <option value='0' >Wybierz pracownika...</option>");
                    


                    //Polecenie SQL pobierajace dane pracownikow z baz
                    
                    $komenda_sql_pracownik = 
                    "SELECT IdPracownik, Imie, Nazwisko
                    FROM dbo.Pracownik
                    ORDER BY Nazwisko ASC, Imie ASC;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy_pracownik = sqlsrv_query($polaczenie, $komenda_sql_pracownik);

            // IdPojazd, Marka, Model, RokProdukcji, Imie, Nazwisko
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz_pracownik = sqlsrv_fetch_array($zbior_wierszy_pracownik, SQLSRV_FETCH_ASSOC)) {
                $IdPracownik = $wiersz_pracownik["IdPracownik"];
                $Imie = $wiersz_pracownik["Imie"];
                $Nazwisko = $wiersz_pracownik["Nazwisko"];
                

                if ($IdPracownik == $IdPracownik_wyb) {
                    print("<option value='$IdPracownik' selected='selected'>$Imie $Nazwisko  </option>");
                }
                else {
                    print("<option value='$IdPracownik'>$Imie $Nazwisko </option>");
                }
            }
                

                
                    

                print("</select>
                    </p>");
                    
                // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy_pracownik != null) {
                sqlsrv_free_stmt($zbior_wierszy_pracownik);
            } 




            print("
            </fieldset>
    
            <p>
                <input type='submit' value='Zapisz'>
                <input type='reset' value='Wyczyść pola'>
            </p>
    
            </form>");


            // Zamkniecie polaczenia
            sqlsrv_close($polaczenie);

    } // Else Jezeli zostaly zwrocone dane pracownika
    

    } // else Jezeli polaczenie zostalo nawiazane
} // else Jezeli dane sa poprawne
}
?>

</body>

</html>

