<?php

  function check(array &$errors, $variable, string $key, string $name) {
    if(trim($variable) === '') {
      $errors[$key] = "A(z) {$name} megadása kötelező!";
    } else {
      if(!intval(trim($variable))) {
        $errors[$key] = "A(z) {$name} egész szám kell legyen!";
      }
    }
  }

  session_start();

  include "storage.php";

  $users = new Users(new JsonIO("users.json"), true);
  $cards = new Cards(new JsonIO("cards.json"), true);

  if(isset($_SESSION['user_id'])) {
    $user = $users->findById($_SESSION['user_id']);
    if(!$user["admin"]) {
      header("location: index.php");
    }
  } else {
    header("location: index.php");
  }

  $card_name = $_POST['card_name'] ?? '';
  $type = $_POST['type'] ?? '';
  $hp = $_POST['hp'] ?? '';
  $attack = $_POST['attack'] ?? '';
  $defense = $_POST['defense'] ?? '';
  $price = $_POST['price'] ?? '';
  $description = $_POST['description'] ?? '';
  $image = $_POST['image'] ?? '';

  $types = ["electric", "fire", "grass", "water", "bug", "normal", "poison"];

  if(!empty($_POST)) {
    $errors = [];

    if(trim($card_name) === '') {
      $errors['card_name'] = 'A(z) kártyanév megadása kötelező!';
    }

    if(trim($type) === '') {
      $errors['type'] = 'A(z) típus megadása kötelező!';
    } else  {
      if(!in_array(trim($type), $types)) {
        $errors['type'] = 'A típus nem megfelelő!';
      }
    }

    check($errors, $hp, 'hp', 'életerő');
    check($errors, $attack, 'attack', 'támadás');
    check($errors, $defense, 'defense', 'védekezés');
    check($errors, $price, 'price', 'ár');

    if(trim($description) === '') {
      $errors['description'] = 'A(z) leírás megadása kötelező!';
    }

    if(trim($image) === '') {
      $errors['image'] = 'A(z) kép megadása kötelező!';
    } else {
      if(!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = 'A(z) képhez tartozó url hibás!';
      }
    }

    $errors = array_map(fn($e) => "<span style='color: red'> $e </span>", $errors);

    if(empty($errors)) {
      $cards = new Cards(new JsonIO("cards.json"), true);

      $nextId = $cards->getNextId();

      $cards->updateRecord([
        "name" => $card_name,
        "type" => $type,
        "hp" => $hp,
        "attack" => $attack,
        "defense" => $defense,
        "price" => $price,
        "description" => $description,
        "image" => $image
      ]);

      $users->addCard("admin", $nextId);

      header("location:index.php");
    }
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
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>
<header>
    <nav>
      <ul>
        <li><a href="index.php">Főoldal</a></li>
        <li><a href="card.php">Új kártya</a></li>
        <li><a href="logout.php">Kijelentkezés</a></li>
      </ul>
    </nav>
  </header>

  <div id="content">
    <h2> Kártya létrehozás </h2>
    <div class="container">
      <form action="card.php" method="post">
        Név: <input type="text" name="card_name" value="<?= $card_name ?>">
          <?= $errors['card_name'] ?? '' ?><br>
        Típus:<br>
        <select name="type" id="type">
          <?php foreach($types as $ctype): ?>
            <option value="<?= $ctype ?>"<?php echo ($type === $ctype) ? 'selected' : ''; ?>><?= $ctype ?></option>
          <?php endforeach; ?>
        </select><br>
        Életerő: <input type="text" name="hp" value="<?= $hp ?>">
          <?= $errors['hp'] ?? '' ?><br>
        Támadás: <input type="text" name="attack" value="<?= $attack ?>">
          <?= $errors['attack'] ?? '' ?><br>
        Védekezés: <input type="text" name="defense" value="<?= $defense ?>">
          <?= $errors['defense'] ?? '' ?><br>
        Ár: <input type="text" name="price" value="<?= $price ?>">
          <?= $errors['price'] ?? '' ?><br>
        Leírás: <input type="text" name="description" value="<?= $description ?>">
          <?= $errors['description'] ?? '' ?><br>
        Kép: <input type="text" name="image" value="<?= $image ?>">
          <?= $errors['image'] ?? '' ?><br>
        <button type="submit">Létrehozás</button>
      </form>
    </div>
  </div>

  <footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
  </footer>
</body>

</html>