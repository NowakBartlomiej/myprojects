<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Usuń Pracownika</title>
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
            print("<p class='msg error'>Nie można usunąć danych pracownika, ponieważ nie został on wybrany</p>");

            die("<p><a class='btn def' href='pracownik_tabela.php'>Powrót do wykazu pracowników</a></p>");
        } // if jezeli dane sa niepoprawne
        // else Jezeli zostal przekazany klucz wiersza do usuniecia
        else {
    
            // Pobranie klucza wiersza do edycji
            $IdPracownik = trim($_GET["IdPracownik"]);

            print("<h2>Usuwanie danych pracownika</h2>");

        // Polecenie SQL pobierajace wiersze z tablei bd.
        print("<p class='msg warn'>Czy na pewno usunąć dane pracownika o identyfikatorze <strong>$IdPracownik</strong>?</p>");

        print("<p><br><a class='btn del' href='pracownik_usun_potw.php?IdPracownik=$IdPracownik'>Tak, usuń</a> <a class='btn' href='pracownik_tabela.php'>Nie, wróć do wykazu</a></p>");


     // else Jezeli polaczenie zostalo nawiazane
} // else Jezeli dane sa poprawne
}
?>

</body>

</html>

