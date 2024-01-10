<?php

  session_start();

  include "storage.php";

  $user = null;

  if(isset($_SESSION['user_id'])) {
    $storage = new Storage(new JsonIO("users.json"), true);
    $user = $storage->findById($_SESSION['user_id']);
  }

  $cards = new Storage(new JsonIO("cards.json"), true);
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
    <h2>Felhasználó adatai</h2>
    <ul>
      <li>Felhasználónév: <?= $user['user_name'] ?></li>
      <li>Email: <?= $user['email'] ?></li>
      <li>Pénz: <?= $user['money'] ?></li>
    </ul>
    <h2> Kártyái </h2>
    <div id="card-list">
      <?php foreach($user['cards'] as $cardId):
              $card = $cards->findById($cardId); ?>
        <div class="pokemon-card">
          <div class="image clr-<?= $card["type"] ?>">
            <img src="<?= $card["image"] ?>" alt="">
          </div>
          <div class="details">
              <h2><a href="details.php?id=<?= $cardId ?>"><?= $card["name"] ?></a></h2>
              <span class="card-type"><span class="icon">🏷</span> <?= $card['type'] ?></span>
              <span class="attributes">
                  <span class="card-hp"><span class="icon">❤</span> <?= $card['hp'] ?></span>
                  <span class="card-attack"><span class="icon">⚔</span> <?= $card['attack'] ?></span>
                  <span class="card-defense"><span class="icon">🛡</span> <?= $card['defense'] ?></span>
              </span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
  </footer>
</body>

</html>