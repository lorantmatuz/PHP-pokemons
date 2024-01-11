<?php

    include "storage.php";

    session_start();

    $user = null;
    $isAdmin = false;
    $users = new Users(new JsonIO("users.json"), true);
    $cards = new Cards(new JsonIO("cards.json"), true);

    if(isset($_SESSION['user_id'])) {
      $user = $users->findById($_SESSION['user_id']);
      $isAdmin = $user["admin"];
    }

    if(isset($_GET["id"])) {
        $card = $cards->findById($_GET["id"]);
        if($card === null) {
            header("location: index.php");
        }
    } else {
        header("location: index.php");
    }

?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
      <nav>
          <ul>
              <li><a href="index.php">Főoldal</a></li>
              <?php if(isset($_SESSION['user_id'])): ?>
                  <?php if($isAdmin): ?>
                    <li><a href="card.php">Új kártya</a></li>
                  <?php else: ?>
                      <li><a href="user.php"><?= $_SESSION['user_id'] ?></a></li>
                      <li><a href="user.php">💰<?= $user['money'] ?></a></li>
                  <?php endif; ?>
                  <li><a href="logout.php">Kijelentkezés</a></li>
              <?php else: ?>
                  <li><a href="login.php">Bejelentkezés</a></li>
                  <li><a href="registration.php" >Regisztráció</a></li>
              <?php endif; ?>
          </ul>
      </nav>
  </header>
    <div id="content">
        <h1> <?= $card["name"] ?> </h1>
        <div id="details">
            <div class="image clr-<?= $card["type"] ?>">
                <img src=<?= $card["image"] ?> alt="">
            </div>
            <div class="info">
                <div class="description"><?= $card["description"] ?></div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?= $card["type"] ?></span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health: <?= $card["hp"] ?></div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack: <?= $card["attack"] ?></div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense: <?= $card["defense"] ?></div>
                </div>
                <div class="detail-buy">
                    <?php if(!$isAdmin): ?>
                        <form method="post" action="<?= isset($_SESSION['user_id']) ? 'index.php' : 'login.php' ?>">
                            <input type="hidden" name="card_id" value="<?= $cardId ?>">
                            <input class="buy" type="submit" name="buy" value="Vásárlás" />
                        </form>
                        <?php endif; ?>
                    </div>
        </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>