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
    
    // http://localhost/LABY/P31_C8/pracownik_dodaj.php?IdPracownik=12&Imie=Jan&Nazwisko=Schwarz&Stanowisko=Logistyk&NrTelefonu=%2B48+623+237+733&AdresEmail=j.schwarz%40firma.pl

    // Sprawdzenie poprawnosci i kompletnosci danych wyslanych z formularza
    if (!isset($_GET["IdPracownik"]) 
        || (trim($_GET["IdPracownik"]) == "")
        || !is_numeric($_GET["IdPracownik"])
        || !isset($_GET["Imie"])
        || (trim($_GET["Imie"]) == "")
        || !isset($_GET["Nazwisko"])
        || (trim($_GET["Nazwisko"]) == "")
        || !isset($_GET["Stanowisko"])
        || (trim($_GET["Stanowisko"]) == "")
        || !isset($_GET["NrTelefonu"])
        || (trim($_GET["NrTelefonu"]) == "")
        || !isset($_GET["AdresEmail"])
        || (trim($_GET["AdresEmail"]) == "")
        ) {
            print("<p class='msg error'>Nie można zapisać danych pracownika, ponieważ są one niekompletne lub błedne</p>");

            die("<p><a class='btn def' href='pracownik_tabela.php'>Powrót do wykazu pracowników</a></p>");
        } // if jezeli dane sa niepoprawne
        // else Jezeli dane sa poprawne
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
        
        // Pobranie zminnych wyslanych z formularza
        $IdPracownik = trim($_GET["IdPracownik"]);
        $Imie = trim($_GET["Imie"]);
        $Nazwisko = trim($_GET["Nazwisko"]);
        $Stanowisko = trim($_GET["Stanowisko"]);
        $NrTelefonu = trim($_GET["NrTelefonu"]);
        $AdresEmail = trim($_GET["AdresEmail"]);

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "UPDATE dbo.Pracownik
        SET  
            Imie = '$Imie', 
            Nazwisko = '$Nazwisko', 
            Stanowisko = '$Stanowisko', 
            NrTelefonu = '$NrTelefonu',
            AdresEmail = '$AdresEmail'
        WHERE IdPracownik = $IdPracownik;";

        //print("<p>Komenda SQL: $komenda_sql</p>");

        // Wykonanie polecenia SQL na serwerze bd.
        $rezultat = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli wykonanie polecenia nie powiodlo sie
        if ($rezultat == false) {
            print("<p class='msg error'>Zapisanie danych pracownika <strong>$Imie $Nazwisko</strong> w bazie nie powiodlo się</p>");
        }
        // Jezeli polecenie zostalo wykonane prawidlowo
        else {
            print("<p class='msg success'>Dane pracownika <strong>$Imie $Nazwisko</strong> zostały zapisane w bazie</p>");
            print("<p><a class='btn def' href='pracownik_tabela.php'>Powrót do wykazu pracowników</a></p>");

            

            // Zwolnienie pamieci zarezerwowanej na wynik polecenia
            if ($rezultat != null) {
                sqlsrv_free_stmt($rezultat);
            } 

        } 

        // Zamkniecie polaczenia
        sqlsrv_close($polaczenie);
    

    } // else Jezeli polaczenie zostalo nawiazane
} // else Jezeli dane sa poprawne
}
?>

</body>

</html>