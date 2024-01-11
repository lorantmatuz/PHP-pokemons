<?php

  session_start();

  include "storage.php";

  $user = null;
  $isAdmin = false;
  $users = new Users(new JsonIO("users.json"), true);

  if(isset($_SESSION['user_id'])) {
    $user = $users->findById($_SESSION['user_id']);
    $isAdmin = $user["admin"];
  }

  $cards = new Cards(new JsonIO("cards.json"), true);
  $filteredCardIds = $cards->getKeys();

  $filter = '';

  if(isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    if($filter !=='') {
        $filteredCardIds = array_keys($cards->findAll(["type" => $filter]));
    } else {
        $filteredCardIds = $cards->getKeys();
    }
  } else {
    $filteredCardIds = $cards->getKeys();
  }

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
  <header>
      <nav>
          <ul>
              <li><a href="index.php">Főoldal</a></li>
              <?php if(isset($_SESSION['user_id'])): ?>
                  <?php if($isAdmin): ?>
                    <li><a href="user.php">Admin oldal</a></li>
                    <li><a href="card.php">Új kártya</a></li>
                  <?php else: ?>
                      <li>
                          <a href="user.php"><?= $_SESSION['user_id'] ?> - 💰<?= $user['money'] ?></a>
                      </li>
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
        <h1> Kártyák </h1>
        <form method="get">
        <label for="filter">Szűrés típus szerint:</label>
        <select name="filter" id="filter" onchange="this.form.submit()">
            <option value="" <?php echo ($filter === '') ? 'selected' : ''; ?>>Összes</option>
            <option value="electric"<?php echo ($filter === 'electric') ? 'selected' : ''; ?>>Electric</option>
            <option value="fire"    <?php echo ($filter === 'fire') ? 'selected' : ''; ?>>Fire</option>
            <option value="grass"   <?php echo ($filter === 'grass') ? 'selected' : ''; ?>>Grass</option>
            <option value="water"   <?php echo ($filter === 'water') ? 'selected' : ''; ?>>Water</option>
            <option value="bug"     <?php echo ($filter === 'bug') ? 'selected' : ''; ?>>Bug</option>
            <option value="normal"  <?php echo ($filter === 'normal') ? 'selected' : ''; ?>>Normal</option>
            <option value="poison"  <?php echo ($filter === 'poison') ? 'selected' : ''; ?>>Poison</option>
        </select>
    </form>
        <div id="card-list">

      <?php foreach($filteredCardIds as $cardId):
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