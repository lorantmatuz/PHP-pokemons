<?php

  function filtering($cards, array $arr, $filter) {
    $res = [];
    foreach($arr as $cardId) {
      if($cards->findById($cardId)["type"] === $filter) {
        array_push($res, $cardId);
      }
    }
    return $res;
  }

  function functionAlert($message) {
    echo "<script>alert('$message');</script>";
  }

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
  $filteredCardIds =  $users->adminCards();

  $filter = '';

  if(isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    if($filter !=='') {
      $filteredCardIds = filtering($cards, $filteredCardIds, $filter);
    }
  }

  // buy it
  if(isset($_POST['card_id'])) {
    $cardId = $_POST['card_id'];
    $price = $cards->findById($cardId)["price"];
    if(!$users->updateByValueOfId($_SESSION['user_id'], -$price)) {
      functionAlert("A kiválasztott kártya túl drága!");
    } else {
      if(!$users->addCard($_SESSION['user_id'], $cardId)) {
        functionAlert("Maximális számú kártyája van már!");
      }
      else {
        $users->deleteCard("admin", $cardId);
        header("location: index.php");
      }
    }
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
    <link rel="stylesheet" href="styles/login.css">
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
        <h1> <?= $isAdmin ? "Admin kártyák" : "Kártyák" ?></h1>
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
            <a href="details.php?id=<?= $cardId ?>">
              <img src="<?= $card["image"] ?>" alt="">
            </a>
          </div>
          <div class="details">
              <h2><a href="details.php?id=<?= $cardId ?>"><?= $card["name"] ?></a></h2>
              <span class="card-type"><span class="icon">🏷</span> <?= $card['type'] ?></span>
              <span class="attributes">
                  <span class="card-hp"><span class="icon">❤</span> <?= $card['hp'] ?></span>
                  <span class="card-attack"><span class="icon">⚔</span> <?= $card['attack'] ?></span>
                  <span class="card-defense"><span class="icon">🛡</span> <?= $card['defense'] ?></span>
                </span>
              <span class="card-price"> <span class="icon">💰</span><?= $card["price"] ?> </span>
          </div>
          <?php if(!$isAdmin): ?>
            <form method="post" action="<?= isset($_SESSION['user_id']) ? 'index.php' : 'login.php' ?>">
              <input type="hidden" name="card_id" value="<?= $cardId ?>">
              <input class="buy" type="submit" name="buy" value="Vásárlás" />
            </form>
          <?php endif; ?>
          </div>
      <?php endforeach; ?>

    </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>