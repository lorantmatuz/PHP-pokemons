<?php

    if(isset($_GET["id"])) {
        $cards = json_decode(file_get_contents("cards.json"), true);
        if(isset($cards[$_GET["id"]])) {
            $card = $cards[$_GET["id"]];
        } else {
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