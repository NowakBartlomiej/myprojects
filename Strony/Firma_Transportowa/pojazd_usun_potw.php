<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Usuń Pojazd</title>
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



    // Sprawdzenie poprawnosci i kompletnosci danych wyslanych z formularza
    if (!isset($_GET["IdPojazd"]) 
        || (trim($_GET["IdPojazd"]) == "")
        || !is_numeric($_GET["IdPojazd"])
        ) {
            print("<p class='msg error'>Nie można usunąć danych pojazdu, ponieważ są one niekompletne lub błedne</p>");

            die("<p><a class='btn def' href='pojazd_tabela.php'>Powrót do wykazu pojazdów</a></p>");
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
        $IdPojazd = trim($_GET["IdPojazd"]);
        
        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "DELETE dbo.Pojazd
        WHERE IdPojazd = $IdPojazd;";

        //print("<p>Komenda SQL: $komenda_sql</p>");

        // Wykonanie polecenia SQL na serwerze bd.
        $rezultat = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli wykonanie polecenia nie powiodlo sie
        if ($rezultat == false) {
            print("<p class='msg error'>Usunięcie danych pojazdu <strong>$IdPojazd</strong> z bazy nie powiodlo się</p>");
        }
        // Jezeli polecenie zostalo wykonane prawidlowo
        else {
            print("<p class='msg success'>Dane pojazdu <strong>$IdPojazd</strong> zostały usunięte z bazy</p>");
            print("<p><a class='btn def' href='pojazd_tabela.php'>Powrót do wykazu pojazdów</a></p>");

            

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