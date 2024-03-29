<?php

  session_start();

  if(isset($_SESSION['user_id'])) {
    header("location: index.php");
  }

  include "storage.php";

  $user_name = $_POST["user_name"] ?? '';
  $password = $_POST["password"] ?? '';

  if(!empty($_POST) && !isset($_POST['buy'])) {
    $errors = [];

    if(trim($user_name) === '') {
      $errors['user_name'] = 'A felhasználónév megadása kötelező!';
    }
    if(trim($password) === '') {
      $errors['password'] = 'A jelszó megadása kötelező!';
    }

    $storage = new Storage(new JsonIO("users.json"), true);
    $user = $storage->findById($user_name);
    if($user !== null) {
      if(password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_name"];
        header("location:index.php");
      }
      else {
        if(!array_key_exists("password", $errors)) {
          $errors["password"] = "A jelszó nem megfelelő!";
        }
      }
    }
    else {
      if(!array_key_exists("user_name", $errors)) {
        $errors["user_name"] = "Nem létező felhasználónév!";
      }
    }

    $errors = array_map(fn($e) => "<span style='color: red'> $e </span>", $errors);

  }

?>

<!DOCTYPE html>
<html lang="hu">

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

    <div id="content">
      <h1>Bejelentkezés</h1>
      <div class="container">
        <form action="login.php" method="post">
          Felhasználónév: <input type="text" name="user_name" value="<?= $user_name ?>">
            <?= $errors["user_name"] ?? '' ?> <br>
          Jelszó: <input type="password" name="password" value="<?= $password ?>">
            <?= $errors["password"] ?? '' ?> <br>
          <button type="submit">Bejelentkezés</button>
        </form>
      </div>
    </div>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>