<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pdo = new PDO($dbh, $user, $pass);
$stmt = $pdo->prepare('SELECT id, full_name, email, phone FROM users WHERE email = :email');
$stmt->execute([':email'=> $_COOKIE['login']]);
$userData = $stmt->fetch();
$stmt = $pdo->prepare('SELECT group_id, name FROM `user_group` JOIN `groups` ON group_id = groups.id WHERE user_id = :userId');
$stmt->execute([':userId' => $userData['id']]);
$groups = $stmt->fetchAll();
?>

<ul>
  <li>ФИО: <span><?=$userData['full_name']?></span></li>
  <li>Email: <span><?=$userData['email']?></span></li>
  <?php if ($userData['phone']) {?>
    <li>Номер телефона: <span><?=$userData['phone']?></span></li>
  <?php } else {?>
    <li>Номер телефона: <span>Номер не указан</span></li>
  <?php } ?>
    <li>
      <p>Группы:</p>
      
      <ul>
        <?php foreach ($groups as $group): ?>
          <li><?=$group['name']?></li>
        <?php endforeach ?>
      </ul>
    </li>
</ul>
