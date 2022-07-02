<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Widok Szczegółowy Zamówień</title>
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
    if (!isset($_GET["IdZamowienie"]) 
        || (trim($_GET["IdZamowienie"]) == "")
        || !is_numeric($_GET["IdZamowienie"])
        ) {
            print("<p class='msg error'>Nie można wyświetlić danych zamówienia, ponieważ nie zostało ono wybrane</p>");

            die("<p><a class='btn def' href='zamowienie_tabela.php'>Powrót do wykazu zamówień</a></p>");
        } // if jezeli dane sa niepoprawne
        // else Jezeli zostal przekazany klucz wiersza do wyswietlenia
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
        $IdZamowienie = trim($_GET["IdZamowienie"]);

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT IdZamowienie, DataZlozenia, DataGodzOdbioru, DataGodzDostawy, Zamowienie.Uwagi AS [Uwagi], dbo.Klient.Imie AS [KlientImie], dbo.Klient.Nazwisko AS [KlientNazwisko], Pracownik.Imie AS [PracownikImie], Pracownik.Nazwisko AS [PracownikNazwisko], Stanowisko, Pojazd.Marka AS [PojazdMarka], Pojazd.Model AS [PojazdModel], Miejscowosc, Ulica, NrBudynku, StatusNazwa
        FROM dbo.Zamowienie
            INNER JOIN dbo.Klient
            ON dbo.Zamowienie.IdKlient = dbo.Klient.IdKlient
                INNER JOIN dbo.ZamowienieStatus
                ON dbo.Zamowienie.IdZamowienieStatus = dbo.ZamowienieStatus.IdZamowienieStatus
                    INNER JOIN dbo.Pracownik
                    ON dbo.Zamowienie.IdPracownikKierowca = dbo.Pracownik.IdPracownik
                        INNER JOIN dbo.Pojazd 
                        ON dbo.Zamowienie.IdPojazd = dbo.Pojazd.IdPojazd
                            INNER JOIN dbo.Adres
                            ON dbo.Adres.IdAdres = dbo.Zamowienie.AdresOdbioru
        WHERE IdZamowienie = $IdZamowienie;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) {
            print("<p class='msg error'>W bazie nie ma zapisanych danych zamowienia o identyfikatorze <strong>$IdZamowienie</strong></p>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC)) {
                
                // IdZamowienie, DataZlozenia, DataGodzOdbioru, DataGodzDostawy, Zamowienie.Uwagi AS [Uwagi], dbo.Klient.Imie AS [KlientImie], dbo.Klient.Nazwisko AS [KlientNazwisko], Pracownik.Imie AS [PracownikImie], Pracownik.Nazwisko AS [PracownikNazwisko], Stanowisko, Pojazd.Marka AS [PojazdMarka], Pojazd.Model AS [PojazdModel], Miejscowosc, Ulica, NrBudynku, StatusNazwa

                $DataZlozenia = $wiersz["DataZlozenia"]->format("Y-m-d");
                $DataGodzOdbioru = $wiersz["DataGodzOdbioru"]->format("Y-m-d");
                if (($wiersz["DataGodzDostawy"] != null) && ($wiersz["DataGodzDostawy"] != "")) {
                    $DataGodzDostawy = $wiersz["DataGodzDostawy"]->format("Y-m-d H:i:s");
                } else {
                    $DataGodzDostawy = "nie dotyczy";
                }
                if (($wiersz["Uwagi"] != null) && (trim($wiersz["Uwagi"]) != "")) {
                    $Uwagi = $wiersz["Uwagi"];
                } else {
                    $Uwagi = "brak";
                }
                $KlientImie = $wiersz["KlientImie"];
                $KlientNazwisko = $wiersz["KlientNazwisko"];
                

                $PracownikImie = $wiersz["PracownikImie"];
                $PracownikNazwisko = $wiersz["PracownikNazwisko"];

                $Stanowisko = $wiersz["Stanowisko"];
                
                $PojazdMarka = $wiersz["PojazdMarka"];
                $PojazdModel = $wiersz["PojazdModel"];
                
                $OdbiorMiejscowosc = $wiersz["Miejscowosc"];
                $OdbiorUlica = $wiersz["Ulica"];
                $OdbiorNrBudynku = $wiersz["NrBudynku"];
                
                $StatusNazwa = $wiersz["StatusNazwa"];

                
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

            
            print("<h1>Szczegoly zamówienia numer $IdZamowienie</h1>");
            print("
                <ul class='zam'>
                    <li>Zamawiający: <strong>$KlientImie $KlientNazwisko</strong></li>
                    <li>Data złożenia: <strong>$DataZlozenia</strong></li>
                    <li>Data odbioru: <strong>$DataGodzOdbioru</strong></li>
                    <li>Data dostawy: <strong>$DataGodzDostawy</strong></li>
                    <li>Miejsce odbioru zamówienia: <strong>$OdbiorMiejscowosc, $OdbiorUlica $OdbiorNrBudynku</strong></li>
                    <li>Pracownik odpowiedzialny: <strong>$PracownikImie $PracownikNazwisko ($Stanowisko)</strong></li>
                    <li>Stan: <strong>$StatusNazwa</strong></li>
                </ul>
            ");
        
    


            // IdTowar, KategoriaNazwa, TowarNazwa, Ilosc, LacznaMasaKG, CenaPrzewozu + Kwota AS [KwotaLaczna] , WartoscTowaru, Miejscowosc, Ulica, NrBudynku
            print("<h3>Zamówiony towar</h3>");
        print("<table>
                <thead>
                    <tr>
                        <td>Kategoria</td>
                        <td>Towar</td>
                        <td>Ilość</td>
                        <td>Łączna masa [KG]</td>
                        <td>Wartość towaru [PLN]</td>
                        <td>Kwota łączna [PLN]</td>
                        <td>Adres dostawy</td>
                    
                    </tr>
                </thead>
                <tbody>");
        

        // WIERSZE TABELI

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql_poz = 
        "SELECT IdTowar, KategoriaNazwa, TowarNazwa, Ilosc, LacznaMasaKG, CenaPrzewozu + Kwota AS [KwotaLaczna] , WartoscTowaru, Miejscowosc, Ulica, NrBudynku
        FROM dbo.Towar
            INNER JOIN dbo.Kategoria
            ON dbo.Towar.IdKategoria = dbo.Kategoria.IdKategoria
                INNER JOIN dbo.Zamowienie
                ON dbo.Towar.IdZamowienie = dbo.Zamowienie.IdZamowienie
                    INNER JOIN dbo.Adres
                    ON dbo.Zamowienie.AdresDostawy = dbo.Adres.IdAdres
        WHERE Towar.IdZamowienie = $IdZamowienie;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy_poz = sqlsrv_query($polaczenie, $komenda_sql_poz);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy_poz) == false) {
            print("<tr>
                    <td colspan='7'>Brak zamówionych towarów</td>
                </tr>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {

            // KategoriaNazwa, TowarNazwa, Ilosc, LacznaMasaKG, CenaPrzewozu + Kwota AS [KwotaLaczna] , WartoscTowaru, Miejscowosc, Ulica, NrBudynku
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz_poz = sqlsrv_fetch_array($zbior_wierszy_poz, SQLSRV_FETCH_ASSOC)) {
                $KategoriaNazwa = $wiersz_poz["KategoriaNazwa"];
                $TowarNazwa = $wiersz_poz["TowarNazwa"];
                $Ilosc = $wiersz_poz["Ilosc"];
                $LacznaMasaKG = number_format($wiersz_poz["LacznaMasaKG"],2);
                $KwotaLaczna = number_format($wiersz_poz["KwotaLaczna"], 2);
                $WartoscTowaru = number_format($wiersz_poz["WartoscTowaru"],2);
                $DostawaMiejscowosc = $wiersz_poz["Miejscowosc"];
                $DostawaUlica = $wiersz_poz["Ulica"];
                $DostawaNrBudynku = $wiersz_poz["NrBudynku"];

        

                print("<tr>
                        <td>$KategoriaNazwa</td>
                        <td>$TowarNazwa</td>
                        <td>$Ilosc</td>
                        <td>$LacznaMasaKG</td>
                        <td>$WartoscTowaru</td>
                        <td>$KwotaLaczna</td>
                        <td>$DostawaMiejscowosc, $DostawaUlica $DostawaNrBudynku</td>
                    </tr>");
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy_poz != null) {
                sqlsrv_free_stmt($zbior_wierszy_poz);
            } 

        } // Else Jezeli zostaly zwrocone wiersze

        print("</tbody>
            </table>");
        



            

            // Zamkniecie polaczenia
            

    } // Else Jezeli zostaly zwrocone dane zamowienia
        sqlsrv_close($polaczenie);

    } // else Jezeli polaczenie zostalo nawiazane
    die("<p><a class='btn def' href='zamowienie_tabela.php'>Powrót do wykazu zamówień</a></p>");
} // else Jezeli dane sa poprawne
}
?>

</body>

</html>

