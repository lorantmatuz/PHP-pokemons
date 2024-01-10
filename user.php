<?php

  session_start();

  include "storage.php";

  $user = null;

  if(isset($_SESSION['user_id'])) {
    $storage = new Storage(new JsonIO("users.json"), true);
    $user = $storage->findById($_SESSION['user_id']);
  }

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználói oldal</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
  <header>
        <nav>
            <ul>
                <li><a href="index.php">Főoldal</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="user.php"><?= $_SESSION['user_id'] ?> -
                        💰<?= $user['money'] ?></a></li>
                  <li><a href="logout.php">Kijelentkezés</a></li>
                <?php else: ?>
                  <li><a href="login.php">Bejelentkezés</a></li>
                  <li><a href="registration.php" >Regisztráció</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

  <div id="content">

  </div>

  <footer>
      <p>IKémon | ELTE IK Webprogramozás</p>
  </footer>
</body>

</html>