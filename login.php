<?php
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "login") {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    //obiektowo
    $db = new mysqli("localhost", "root", "", "auth");

    //prepared statements
    $q = $db->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
    //podstaw wartości
    $q->bind_param("s", $email);
    //wykonaj
    $q->execute();
    $result = $q->get_result();

    $userRow = $result->fetch_assoc();
    if ($userRow == null) {
        //konto nie istnieje
        echo "Błędny login lub hasło <br>";
    } else {
        //konto istnieje
        if (password_verify($password, $userRow['passwordHash'])) {
            //hasło poprawne
            echo "Zalogowano poprawnie <br>";
        } else {
            //hasło niepoprawne
            echo "Błędny login lub hasło <br>";
        }
    }
}
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "register") {
//rejestracja nowego użytkownika
    $db = new mysqli("localhost", "root", "", "auth");
    $email = $_REQUEST['email'];
    //wyczyść email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $password = $_REQUEST['password'];
    $passwordRepeat = $_REQUEST['passwordRepeat'];

    if($password == $passwordRepeat) {
        //hasła są identyczne - kontynuuj
        $q = $db->prepare("INSERT INTO user VALUES (NULL, ?, ?)");
        $passwordHash = password_hash($password, PASSWORD_ARGON2I);
        $q->bind_param("ss", $email, $passwordHash);
        $result = $q->execute();
        if($result) {
            echo "Konto utworzono poprawnie"; 
        } else {
            echo "Coś poszło nie tak!";
        }
    } else {

        echo "Hasła nie są zgodne - spróbuj ponownie!";
    }
}


?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css"/>
    <link
      href="https://fonts.googleapis.com/css2?family=Sora:wght@700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Logowanie</title>
    <style>
       
    </style>
</head>

<body>

    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-logo">
                <h1>Gamessite</h1>
            </div>
            <div class="login-card-header">
                <h2>Zaloguj się</h2>
                <div>Zaloguj się, aby korzystać z platformy!</div>
            </div>
            <form class="login-card-form">
                <div class="form-item">
                    <span class="form-item-icon material-symbols-rounded">login</span>
                    <input type="email" name="email" placeholder="E-mail" id="emailInput" 
                    autofocus required>
                </div>
                <div class="form-item">
                    <span class="form-item-icon material-symbols-rounded">lock</span>
                    <input type="password" name="password" placeholder="Hasło" id="passwordInput"
                     required>
                </div>
                <div class="form-item-other">
                    <div class="checkbox">
                        <input type="checkbox" id="rememberMeCheckbox" checked>
                        <label for="rememberMeCheckbox">Zapamiętaj mnie</label>
                    </div>
                    <a href="#">Zapomniałem hasła</a>
                </div>
                <input type="hidden" name="action" value="login">
                <input type="submit" class="log" value="Zaloguj">
            </form>
            <div class="login-card-footer">
                Nie masz konta? <a href="register.php">Zarejestruj się za darmo!</a>
            </div>
            <div class="login-card-footer">
            <a href="gry.html">Przejdź do platformy</a>
        </div>
        </div>
        
    </div>

</body>

</html>