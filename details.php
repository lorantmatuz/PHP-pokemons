<?php

    include "storage.php";

    session_start();

    $user = null;

    if(isset($_SESSION['user_id'])) {
      $storage = new Storage(new JsonIO("users.json"), true);
      $user = $storage->findById($_SESSION['user_id']);
    }

    if(isset($_GET["id"])) {
        $storage = new Storage(new JsonIO("cards.json"), true);
        $card = $storage->findById($_GET["id"]);
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
    <link rel="stylesheet" href="styles/details.css">
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
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>