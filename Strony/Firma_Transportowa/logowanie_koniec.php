<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wylogowanie</title>
    <meta name="keywords" content="serwisy, internetowe, programowanie" />
    <meta name="description" content="Strona utworzona w ramach listy C8." />
    <meta name="author" content="Bartłomiej Nowak" />

    <link rel="Stylesheet" href="style_php.css" type="text/css" />
</head>

<body>

<?php
    print("<h2>Koniec sesji użytkownika</h2>");

    print("<p class='msg success'>Sesja zosstała pomyślnie zakończona</p>");

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
    
    print("<a href='index.html' class='btn marBot'>Wróć do strony startowej</a>");
    print("<br>");
    print("<br>");
    die("<p><a class='btn def' href='logowanie_formularz.php'>Powrót do formularza logowania</a></p>");
    

?>

</body>

</html>

