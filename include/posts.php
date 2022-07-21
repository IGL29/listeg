<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

try {
  $pdo = new PDO($dbh, $user, $pass);
  $userId = null;

  if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
  } else {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute([':email' => $_COOKIE['login']]);
    $userId = ($stmt->fetch())['id'];
  }
  $stmt = $pdo->prepare('SELECT group_id, id FROM user_group JOIN `groups` ON group_id = groups.id WHERE user_id = :userId');
  $stmt->execute([':userId' => $userId]);
  $groups = $stmt->fetchAll();

  $isAllowedWrite = false;
  if (isset($_POST['delete']) && isset($_POST['id']) && $_POST['id']) {
    $stmt = $pdo->prepare('DELETE FROM messages WHERE id = :id');
    $stmt->execute([':id' => $_POST['id']]);
    $stmt->fetch();
  }

  if (!empty($groups)) {
    $isAllowedWrite = array_search(2, array_column($groups, 'id'));
  }

  if ($isAllowedWrite) {
    $stmt = $pdo->prepare('SELECT messages.id, title, message_sections.name, messages.read, messages.created_at FROM messages JOIN message_section ON messages.id = message_id LEFT JOIN message_sections ON section_id = message_sections.id WHERE recipient_id = :userId');
    $stmt->execute([':userId' => $userId]);
    $messages = $stmt->fetchAll();
  }
} catch (PDOException $err) {
  var_dump($err);
}?>

  <ul class="messages-list">
    <?php if ($isAllowedWrite && !empty($messages)) {
      foreach ($messages as $message): ?>
        <li class="messages-list__item messages-item <?=!$message['read'] ? 'messages-item--not-read' : ''?>">
        
        <form method="POST" action="/route/posts/">
          <input style="display: none;" name="id" value="<?=$message['id']?>"></input>
          <input style="position: relative; z-index: 2;"type="submit" name="delete" value="Удалить"></input>
        </form>
          <a class="messages-item__link" href="/route/post/detail.php?id=<?=$message['id']?>">
          </a>
          <p class="messages-item__state"><?=$message['read'] ? 'Прочитано' : 'Не прочитано'?></p>
            <h3 class="messages-item__title"><?=$message['title']?></h3>
            <p class="messages-item__user"><?=$message['name']?></p>
        </li>
      <?php endforeach ?>
    <?php } elseif (empty($messages)) {?>
      <li>У вас нет сообщений</li>
    <?php } else {?>
      <p>Вы сможете отправлять сообщения после прохождения модерации</p>
    <?php } ?>
  </ul>
  <?php if ($isAllowedWrite) { ?>
  <a class="button-message" href="/route/posts/add">Написать сообщение</a>
  <?php  }