<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tablela Z Zamówieniami</title>
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
        
        // IdPojazd, Marka, Model, RokProdukcji, Imie, Nazwisko

        // TABELA PRACOWNIKOW
        print("<h2>Zamówienia</h2>");
        print("<table>
                <thead>
                    <tr>
                        <td>Identyfikator</td>
                        <td>Data złożenia</td>
                        <td>Data odbioru</td>
                        <td>Data dostawy</td>
                        <td>Klient</td>
                        <td>Stan</td>
                        
                        <td></td>
                    </tr>
                </thead>
                <tbody>");
        

        // WIERSZE TABELI

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT IdZamowienie, DataZlozenia, DataGodzOdbioru, DataGodzDostawy, Imie, Nazwisko, StatusNazwa
        FROM dbo.Zamowienie
            INNER JOIN dbo.Klient
            ON dbo.Zamowienie.IdKlient = dbo.Klient.IdKlient
                INNER JOIN dbo.ZamowienieStatus
                ON dbo.Zamowienie.IdZamowienieStatus = dbo.ZamowienieStatus.IdZamowienieStatus
        ORDER BY DataZlozenia DESC;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) {
            print("<tr>
                    <td colspan='8'>Brak danych zamówień w bazie</td>
                </tr>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {

            // IdZamowienie, DataZlozenia, DataGodzOdbioru, DataGodzDostawy, Imie, Nazwisko, StatusNazwa
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC)) {
                $IdZamowienie = $wiersz["IdZamowienie"];
                $DataZlozenia = $wiersz["DataZlozenia"]->format("Y-m-d");
                $DataGodzOdbioru = $wiersz["DataGodzOdbioru"]->format("Y-m-d H:i:s");
                if (($wiersz["DataGodzDostawy"] != null) && ($wiersz["DataGodzDostawy"] != "")) {
                    $DataGodzDostawy = $wiersz["DataGodzDostawy"]->format("Y-m-d H:i:s");
                } else {
                    $DataGodzDostawy = "nie dotyczy";
                }
                $Imie = $wiersz["Imie"];
                $Nazwisko = $wiersz["Nazwisko"];
                $StatusNazwa = $wiersz["StatusNazwa"];

        

                print("<tr>
                        <td>$IdZamowienie</td>
                        <td>$DataZlozenia</td>
                        <td>$DataGodzOdbioru</td>
                        <td>$DataGodzDostawy</td>
                        <td>$Imie $Nazwisko</td>
                        <td>$StatusNazwa</td>
                        <td><a class='btn inf' href='zamowienie_szczegoly.php?IdZamowienie=$IdZamowienie'>Szczegóły</a></td>
                        
                    </tr>");
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

        } // Else Jezeli zostaly zwrocone wiersze

        print("</tbody>
            </table>");

            print("<p><br/><a class='btn def' href='logowanie_koniec.php'>Wyloguj</a></p>");

            // Zamkniecie polaczenia
            sqlsrv_close($polaczenie);

        }
}
?>

</body>

</html>