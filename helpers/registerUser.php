<?php

function registerUser($email, $password, $fullName)
{
  include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
  
  try {
    
    $pdo = new PDO($dbh, $user, $pass);
    $queryRegister = $pdo->prepare("insert into users(full_name, email, password) values(:full_name, :email, :password)");
    $queryRegister->execute([':full_name' => $fullName, ':email' => $email, ':password' => password_hash($password, PASSWORD_BCRYPT)]);
    $queryRegister->fetch();
    $newUserId = $pdo->lastInsertId();

    $queryAddGroup = $pdo->prepare("insert into user_group(user_id, group_id) values(:userId, :groupId)");
    $queryAddGroup->execute([':userId' => $newUserId, ':groupId' => 1]);
    $queryRegister->fetch();

    $queryRegister->errorInfo();
    
    $queryRegister = null;
    $dbh = null;

    return true;
  } catch (PDOException $err) {
    die($err->getMessage());
    echo "Database error:" . $err->getMessage();
  }
  return false;
}
