<?php

  include "storage.php";

  var_dump($_POST);

  $user_name = $_POST['user_name'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';

  if(!empty($_POST)) {
    $errors = [];

    // username
    if(trim($user_name) === '') {
      $errors['user_name'] = 'A felhasználónév megadása kötelező!';
    }
    else {
      $storage = new Storage(new JsonIO('users.json'), true);
      if($storage->findById($user_name) !== null) {
        $errors['user_name'] = 'A felhasználónév egyedi kell legyen!';
      }
    }

    // email
    if(trim($email) === '') {
      $errors['email'] = 'Az email-cím megadása kötelező!';
    } else  {
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Az e-mail formátuma nem megfelelő!';
      }
    }

    // passwords
    if(trim($password) === '') {
      $errors['password'] = 'A jelszó megadása kötelező!';
    }
    if(trim($password2) === '') {
      $errors['password2'] = 'A jelszó megismétlése kötelező!';
    } else {
      if(trim($password) != trim($password2)) {
        $errors['password2'] = 'A jelszók nem egyezők!';
      }
    }

    $errors = array_map(fn($e) => "<span style='color: red'> $e </span>", $errors);

    if(empty($errors)) {
      $storage = new Storage(new JsonIO("users.json"), true);

      $storage->update($user_name, [
        'user_name' => $user_name,
        'email'=> $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'money' => 1000,
        'cards' => [],
        'admin' => false
      ]);

      $storage->__destruct();

      // login and redirect
      session_start();
      $_SESSION["user_id"] = $user_name;
      header("location:index.php");
    }
  }
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

    <div id="content">
      <h1> Regisztráció </h1>
      <div class="container">
        <form action="registration.php" method="post">
          Felhasználónév: <input type="text" name="user_name" value="<?= $user_name ?>">
            <?= $errors['user_name'] ?? '' ?><br>
          E-mail cím: <input type="text" name="email" value="<?= $email ?>">
            <?= $errors['email'] ?? '' ?><br>
          Jelszó: <input type="password" name="password" value="<?= $password ?>">
            <?= $errors['password'] ?? '' ?><br>
          Jelszó újra: <input type="password" name="password2" value="<?= $password2 ?>">
            <?= $errors['password2'] ?? '' ?><br>
          <button type="submit">Regisztráció</button>
        </form>
      </div>
    </div>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>