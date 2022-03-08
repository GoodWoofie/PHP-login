<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="wyglond.css">
    <title>Document</title>
</head>

<body>
    <?php
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login') {
        $db = new mysqli("localhost", "root", "", "serwer_logowania");

        $email = $_REQUEST['Email'];
        $password = $_REQUEST['Password'];

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $q = $db->prepare("SELECT * FROM dane WHERE email=? LIMIT 1");
        $q->bind_param("s", $email);
        $q->execute();

        $result = $q->get_result();

        $userRow = $result->fetch_assoc();
        if ($userRow == null) {
            echo "Błędny login lub hasło";
        } else {
            if (password_verify($password, $userRow['PasswordHash'])) {
                echo "Zalogowano poprawnie <br>";
            } else {
                echo "Błędny login lub hasło";
            }
        }
    }
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {

        $db = new mysqli("localhost", "root", "", "serwer_logowania");

        $email = $_REQUEST['Email'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $name = $_REQUEST['Name'];
        $surname = $_REQUEST['Surname'];

        $password = $_REQUEST['Password'];
        $passwordRepeat = $_REQUEST['PasswordRepeat'];
        if ($password == $passwordRepeat) {
            $q = $db->prepare("INSERT INTO dane VALUES (NULL, ?, ?, ?, ?)");
            $passwordHash = password_hash($password, PASSWORD_ARGON2I);
            $q->bind_param("ssss", $email, $passwordHash, $name, $surname);
            $result = $q->execute();
            if ($result) {
                echo "Konto zostało utworzone poprawnie";
            } else {
                echo "Coś poszło nie tak!";
            }
        } else {
            echo "Hasła nie są zgodne, psróbuj ponownie!";
        }
    }
    ?>
    <div class="row">
        <div class="column">
            <h1>Zaloguj Się</h1>
            <form action='login.php' method="post">
                <div>
                    <label for="EmailInput">Email:</label>
                    <input type="email" name="Email" id="EmailInput">
                </div>
                <div>
                    <label for="PasswordInput">Hasło:</label>
                    <input type="password" name="Password" id="PasswordInput">
                </div>
                <div>
                    <input type="hidden" name="action" value="login">
                    <input type="submit" value="zaloguj">
                </div>
            </form>
        </div>

        <div class="column">
            <h1>Zrejestruj Się</h1>
            <form action="login.php" method="post">
                <div>
                    <label for="EmailInput">Email:</label>
                    <input type="email" name="Email" id="EmailInput">
                </div>
                <div>
                    <label for="PasswordInput">Hasło:</label>
                    <input type="password" name="Password" id="PasswordInput">
                </div>
                <div>
                    <label for="PasswordInput">Ponownie hasło:</label>
                    <input type="password" name="PasswordRepeat" id="PasswordRepeatInput">
                </div>
                <div>
                    <label for="NameInput">Imię:</label>
                    <input type="text" name="Name" id="NameInput">
                </div>
                <div>
                    <label for="ForenameInput">Nazwisko:</label>
                    <input type="text" name="Surname" id="SurnameInput">
                </div>
                <div>
                    <input type="hidden" name="action" value="register">
                    <input type="submit" value="Zarejestruj">
                </div>
            </form>
        </div>
    </div>
</body>

</html>