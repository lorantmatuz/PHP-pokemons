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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Főoldal</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                  <li><a href="logout.php">Kijelentkezés</a></li>
                <?php else: ?>
                  <li><a href="login.php">Bejelentkezés</a></li>
                  <li><a href="registration.php" >Regisztráció</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <h1>Bejelentkezés</h1>

    <div class="container">
      <form action="login.php" method="post">
        Felhasználónév: <input type="text" name="username"> <br>
        Jelszó: <input type="password" name="password"> <br>
        <button type="submit">Bejelentkezés</button>
      </form>
    </div>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>