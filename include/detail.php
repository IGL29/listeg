<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if (http_response_code() === 404) { ?>
  <div>
    <h3>Ошибка 404. Страница не найдена</h3>
  </div>
<?php } else {
  try {
    $pdo = new PDO($dbh, $user, $pass);

    if (isset($_GET['id'])) {
      $stmt = $pdo->prepare('SELECT title, created_at, full_name, messages.read, value FROM messages JOIN users ON sender_id = users.id WHERE messages.id = :id');
      $stmt->execute([':id' => $_GET['id']]);
      $messageData = $stmt->fetch();

      if (!$messageData['read']) {
        $stmt = $pdo->prepare('UPDATE messages SET messages.read = 1 WHERE id = :id');
        $stmt->execute([':id' => $_GET['id']]);
        $stmt->fetch();
      }
    }
  } catch (PDOException $err) {
    var_dump($err);
  }

  if (!empty($messageData)) { ?>

    <div>
      <h3><?=$messageData['title']?></h3>
      <p><?=$messageData['created_at']?></p>
      <p>Отправитель: <?=$messageData['full_name']?></p>
      <p>Сообщение: <?=$messageData['value']?></p>
    </div>
  <?php }
}
