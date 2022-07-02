<?php
    session_name("PSIN");
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Zalogowany</title>
    <meta name="keywords" content="transport, firma, firma transportowa" />
    <meta name="description" content="Strona firmy transportowej w ramach projektu P1" />
    <meta name="author" content="Bartłomiej Nowak" />

    <link rel="Stylesheet" href="style_php.css" type="text/css" />
</head>

<body>

<?php
    print("<h2>Logowanie do serwisu</h2>");
    
    // Sprawdzenie poprawnosci danych z logowania
    if (!isset($_POST["Konto"]) 
        || (trim($_POST["Konto"]) == "")

        || !isset($_POST["Haslo"]) 
        || (trim($_POST["Haslo"]) == "")
        ) {
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

            print("<p class='msg error'>Nieprawidłowa nazwa konta lub hasło (1)</p>");

            die("<p><a class='btn def' href='logowanie_formularz.php'>Powrót do formularza logowania</a></p>");
        } // if jezeli dane sa niepoprawne
        // else Jezeli uzykownik wprowadzil dane logowania
        else {
    
        // Pobieranie danych wprowadzonych przez uzytkownika
        $KontoForm = trim($_POST["Konto"]);
        $HasloForm = trim($_POST["Haslo"]);

        // Zabezpieczenie przed atakami typu SQL injection
        $tablica_znaki = array("-", ";", "~", "`", ":", "(", ")", "{", "}", "[", "]", "+", "!", "@", "#", "$", "%", "&", "%", "^", "&", "*", "[", "]", ",", ".", "?", "<", ">", "\\", "\/", "'");

        $KontoForm = str_ireplace($tablica_znaki, "", $KontoForm);

        //print("<p>KontoForm: $KontoForm</p>");


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
        

        // Polecenie SQL pobierajace wiersze z tablei bd.
        $komenda_sql = 
        "SELECT Imie, Nazwisko, Konto, Haslo, DataZarejestrowania
        FROM dbo.Uzytkownik
        WHERE Konto = '$KontoForm';";

        // Wykonanie polecenia SQL na serwerze bd.
        $zbior_wierszy = sqlsrv_query($polaczenie, $komenda_sql);

        // Jezeli w wyniku zapytania zwrocony zostalpusty zbior wierszy
        if (sqlsrv_has_rows($zbior_wierszy) == false) 
        {
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
            
            print("<p class='msg error'>Nieprawidłowa nazwa konta lub hasło (2)</p>");

            die("<p><a class='btn def' href='logowanie_formularz.php'>Powrót do formularza logowania</a></p>");
        }
        
        // Jezeli zostaly zwrocone wiersze
        else {
            // pobbieranie wiersza z danymi uzytkownika
            $wiersz = sqlsrv_fetch_array($zbior_wierszy, SQLSRV_FETCH_ASSOC);
        
            $Imie = $wiersz["Imie"];
            $Nazwisko = $wiersz["Nazwisko"];
            $Haslo = $wiersz["Haslo"];
            $DataZarejestrowania = $wiersz["DataZarejestrowania"];
            

            // Weryfikacja hasla
            if (password_verify($HasloForm, $Haslo) == true) {
                $_SESSION["zalogowany"] = true;
                $_SESSION["uzytkownik"] = $KontoForm;
                $_SESSION["Imie"] = $Imie;
                $_SESSION["Nazwisko"] = $Nazwisko;

                print("<p class='msg success'>Witaj <strong>$Imie $Nazwisko</strong>!<br/><br/>Jesteś zalogowany(a) jako <strong>$KontoForm</strong></p>");

                print("<p><br/><a class='btn def' href='pracownik_tabela.php'>Przejdź do wykazu pracowników</a></p>");

                print("<p><br/><a class='btn def' href='pojazd_tabela.php'>Przejdź do wykazu pojazdów</a></p>");

                print("<p><br/><a class='btn def' href='zamowienie_tabela.php'>Przejdź do wykazu zamówien</a></p>");

                print("<p><br/><a class='btn def' href='logowanie_koniec.php'>Wyloguj</a></p>");
            } 
            else 
            {
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
                
                print("<p class='msg error'>Nieprawidłowa nazwa konta lub hasło (3)</p>");
    
                die("<p><a class='btn def' href='logowanie_formularz.php'>Powrót do formularza logowania</a></p>");
            }

            // Zwolnienie pamieci zarezerwowanej na wynik zapytania
            if ($zbior_wierszy != null) {
                sqlsrv_free_stmt($zbior_wierszy);
            } 

        }

        // Zamkniecie polaczenia
        sqlsrv_close($polaczenie);
        }
    
    

    } // Else Jezeli zostaly zwrocone dane pracownika
    



?>

</body>

</html>

