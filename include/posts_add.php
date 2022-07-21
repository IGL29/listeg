<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
include $_SERVER['DOCUMENT_ROOT'] . '/helpers/messageValidate.php';

$messageData = null;
$isValid = null;
$isError = false;
$messageForUser = '';

try {
  $pdo = new PDO($dbh, $user, $pass);
  $messageSections = $pdo->query('SELECT id, name FROM message_sections');

  $stmt = $pdo->prepare('SELECT id, full_name, email, phone FROM users WHERE email = :email');
  $stmt->execute([':email'=> $_COOKIE['login']]);
  $userData = $stmt->fetch();

  $users = $pdo->query('SELECT id, full_name FROM users');

  if (isset($_POST['add']) && isset($_POST['title']) && isset($_POST['value']) && isset($_POST['sectionId']) && isset($_POST['recipientId'])) {
    [$messageData, $isValid] = messageValidate($_POST['title'], $_POST['value'], $_POST['sectionId'], $_POST['recipientId']);
  }

  if ($isValid) {
      $stmt = $pdo->prepare('INSERT INTO messages (title, value, created_at, sender_id, recipient_id) VALUES (:title, :value, NOW(), :sender_id, :recipient_id)');
      $stmt->execute([':title' => $messageData['title'], ':value' => $messageData['value'], ':sender_id' => $userData['id'], ':recipient_id' => $messageData['recipientId']]);
      $stmt->fetch();
      $newMessageId = $pdo->lastInsertId();
    
      $stmt = $pdo->prepare('INSERT INTO message_section (message_id, section_id) VALUES (:message_id, :section_id)');
      $stmt->execute([':message_id' => $newMessageId, ':section_id' => $messageData['sectionId']]);
      $stmt->fetch();
      $messageForUser = 'Сообщение отправлено!';
  }
} catch(PDOException $err) {
  $messageForUser = $err;
  $isError = true;
}
?>

<form class="form-add-message" action="/route/posts/add" method="POST">
  <label> Заголовок
    <input name="title" type="text" value="<?=isset($_POST['title']) ? $_POST['title'] : ''?>">
  </label>

  <label> Раздел сообщения
    <select name="sectionId">
      <?php foreach ($messageSections as $messageSection): ?>
      <option <?=isset($_POST['sectionId']) && $_POST['sectionId'] === $messageSection['id'] ? 'selected' : ''?> value="<?=$messageSection['id']?>"><?=$messageSection['name']?></option>
      <?php endforeach ?>
    </select>
  </label>

  <label> Текст сообщения
    <textarea name="value"><?=isset($_POST['value']) ? $_POST['value'] : ''?></textarea>
  </label>

  <label> Получатель
    <select name="recipientId">
      <?php foreach ($users as $user): ?>
      <option <?=isset($_POST['recipientId']) && $_POST['recipientId'] === $user['id'] ? 'selected' : ''?> value="<?=$user['id']?>"><?=$userData['id'] === $user['id'] ? 'Отправить себе' : $user['full_name']?></option>
      <?php endforeach ?>
    </select>
  </label>
  <input type="submit" name="add" value="Отправить"></input>
</form>

<?php if ($isValid === false) {?>
  <p>Все поля должны быть заполнены</p>
<?php }
      if ($isError) {?>
      <p>Произошла ошибка: <?=$messageForUser?></p>
<?php } elseif ($messageForUser) { ?>
      <p><?=$messageForUser?></p>
<?php }?>
