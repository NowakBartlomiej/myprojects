<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Logowanie</title>
    <meta name="keywords" content="transport, firma, firma transportowa" />
    <meta name="description" content="Strona firmy transportowej w ramach projektu P1" />
    <meta name="author" content="Bartłomiej Nowak" />

    <link rel="Stylesheet" href="style_php.css" type="text/css" />
</head>

<body>

<?php
        // FORMULARZ
        print("
        <h2>Logowanie do serwisu</h2>
        <form id='formularzLogowanie' method='post' action='logowanie_weryfikacja.php'>
            <fieldset>
                <legend>Dane użytkownika</legend>
                <p class='lbl'>
                    <label for='Konto'>Nazwa konta*</label>
                    <input type='text' name='Konto' id='Konto' maxlength='30'>
                </p>
    
                <p class='lbl'>
                    <label for='Haslo'>Hasło*</label>
                    <input type='password' name='Haslo' id='Haslo' maxlength='30'>
                </p>
    
            </fieldset>
    
            <p>
                <input type='submit' value='Zaloguj'>
                <input type='reset' value='Wyczyść pola'>
                <a href='index.html' class='btn def'>Wróć</a>
            </p>
    
        </form>");

?>

</body>

</html>

