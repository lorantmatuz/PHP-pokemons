<?php

  session_start();

  if (!isset($_SESSION["user_id"]) && !empty($_POST)) {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $reg = json_decode(file_get_contents("users.json"), true);
    $match = array_keys(array_filter($reg, fn($v) => $v["username"] == $username));

    $id = $match[0] ?? null;

    if ($id !== null) {
      unset($_SESSION["login_error"]);
      if (password_verify($password, $reg[$id]["password"])) {
        $_SESSION["user_id"] = $id;
        header("location:index.php");
      } else {
        $_SESSION["login_error"] = "invalid password for username";
      }
    } else {
      $_SESSION["login_error"] = "invalid username";
    }
  } else {
    session_unset();
  }

  /**
   * Regisztráció során meg kell adni felhasználónevet, az e-mail címet 
   * és a jelszót kétszer. 
   * Mindegyik kötelező, a felhasználónév legyen egyedi,
   *  az e-mail cím formátuma legyen helyes, a jelszavak pedig egyezzenek.
   *  Sikeres regisztráció esetén a felhasználó kapjon x mennyiségű pénzt 
   * (ezt az összeget ajánlott beleégetned a kódba, mert úgyis azt 
   * szeretnénk, hogy minden user ugyanannyi pénzt kapjon).
   */
?>




<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Főoldal</a></li>
                <li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="logout.php">Kijelentkezés</a>
                    <?php else: ?>
                        <a href="login.php">Bejelentkezés</a>
                    <?php endif; ?>
                </li>
                <li><a href="registration.php" >Regisztráció</a></li>
            </ul>
        </nav>
    </header>

    <h1> Regisztráció </h1>

    <div class="container">
      <form action="registration.php" method="post">
        Felhasználónév: <input type="text" name="username"> <br>
        E-mail cím: <input type="text" name="email"> <br>
        Jelszó: <input type="password" name="password"> <br>
        Jelszó újra: <input type="password" name="password"> <br>
        <button type="submit">Regisztráció</button>
      </form>
    </div>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>