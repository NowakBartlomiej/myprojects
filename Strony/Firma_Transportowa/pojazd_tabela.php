<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tabela Pojazdów</title>
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
        //print("<p class='msg success'>Połączenie z serwerem baz danych $serwer OK.</p>");
        
        // IdPojazd, Marka, Model, RokProdukcji, Imie, Nazwisko

        // TABELA PRACOWNIKOW
        print("<h2>Pojazd</h2>");
        print("<table>
                <thead>
                    <tr>
                        <td>Identyfikator</td>
                        <td>Marka</td>
                        <td>Model</td>
                        <td>Rok produkcji</td>
                        <td>Imię</td>
                        <td>Nazwisko</td>
                        
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>");
        

        // WIERSZE TABELI

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT IdPojazd, Marka, Model, RokProdukcji, Imie, Nazwisko
        FROM dbo.Pojazd
            INNER JOIN dbo.Pracownik
            ON dbo.Pojazd.IdPracownik = dbo.Pracownik.IdPracownik
        ORDER BY Nazwisko ASC, Imie ASC;";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) {
            print("<tr>
                    <td colspan='8'>Brak danych pojazdów w bazie</td>
                </tr>");
        }
        // Jezeli zostaly zwrocone wiersze
        else {

            // IdPojazd, Marka, Model, RokProdukcji, Imie, Nazwisko
            // Petla pobierania wierszy ze zbioru (record set)
            while($wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC)) {
                $IdPojazd = $wiersz["IdPojazd"];
                $Marka = $wiersz["Marka"];
                $Model = $wiersz["Model"];
                $RokProdukcji = $wiersz["RokProdukcji"];
                $Imie = $wiersz["Imie"];
                $Nazwisko = $wiersz["Nazwisko"];

                

                print("<tr>
                        <td>$IdPojazd</td>
                        <td>$Marka</td>
                        <td>$Model</td>
                        <td>$RokProdukcji</td>
                        <td>$Imie</td>
                        <td>$Nazwisko</td>
                        <td><a class='btn upd' href='pojazd_edytuj.php?IdPojazd=$IdPojazd'>Edytuj</a></td>
                        <td><a class='btn del' href='pojazd_usun.php?IdPojazd=$IdPojazd'>Usuń</a></td>
                    </tr>");
            } // While Petla pobierania wierszy ze zbioru (record set)

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

        } // Else Jezeli zostaly zwrocone wiersze

        print("</tbody>
            </table>");



        /*
            CREATE TABLE [dbo].[Pojazd](
	[IdPojazd] [int] NOT NULL,
	[IdPracownik] [int] NOT NULL,
	[Marka] [varchar](50) NOT NULL,
	[Model] [varchar](50) NOT NULL,
	[RokProdukcji] [int] NOT NULL,
	[StatusPojazdu] [varchar](50) NOT NULL,
	[Opis] [varchar](200) NULL
        */


        // FORMULARZ
        print("
        <h2>Nowy pojazd</h2>
        <form id='formularzPojazdDodaj' method='get' action='pojazd_dodaj.php'>
            <fieldset>
                <legend>Pojazd i pracownik</legend>
                <p class='lbl'>
                    <label for='IdPojazd'>Identyfikator*</label>
                    <input type='text' name='IdPojazd' id='IdPojazd' maxlength='10'>
                </p>
    
                <p class='lbl'>
                    <label for='Marka'>Marka*</label>
                    <input type='text' name='Marka' id='Marka' maxlength='50'>
                </p>
    
                <p class='lbl'>
                    <label for='Model'>Model*</label>
                    <input type='text' name='Model' id='Model' maxlength='50'>
                </p>
    
                <p class='lbl'>
                    <label for='RokProdukcji'>Rok produkcji*</label>
                    <input type='text' name='RokProdukcji' id='RokProdukcji' maxlength='4'>
                </p>
    
                <p class='lbl'>
                    <label for='StatusPojazdu'>Status pojazdu*</label>
                    <input type='text' name='StatusPojazdu' id='StatusPojazdu' maxlength='50'>
                </p>
                
                <p class='lbl'>
                    <label for='Opis'>Opis</label>
                    <input type='text' name='Opis' id='Opis' maxlength='200'>
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
                
        
                print("<option value='$IdPracownik'>$Imie $Nazwisko </option>");
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

            print("<p><br/><a class='btn def' href='logowanie_koniec.php'>Wyloguj</a></p>");
            
            // Zamkniecie polaczenia
            sqlsrv_close($polaczenie);

        }
}
?>

</body>

</html>