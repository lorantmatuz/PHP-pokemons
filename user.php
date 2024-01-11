<?php

  session_start();

  include "storage.php";

  $users = new Users(new JsonIO("users.json"), true);
  $cards = new Cards(new JsonIO("cards.json"), true);

  if(isset($_SESSION['user_id'])) {
    $user = $users->findById($_SESSION['user_id']);
  }

  // delete from user cards and reload
  if(isset($_POST['card_id'])) {
    $cardId = $_POST['card_id'];
    $price = $cards->findById($cardId)["price"];
    $users->deleteByValueOfId($_SESSION['user_id'], 'cards', $cardId);
    $users->updateByValueOfId($_SESSION['user_id'], 'money', $price * 0.9);
    // TODO: back to admin's cards!
    header("location: user.php");
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
          <form method="post">
            <input type="hidden" name="card_id" value="<?= $cardId ?>">
            <input type="submit" name="sell" value="Eladás" />
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
  </footer>
</body>

</html>