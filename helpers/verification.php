<?php

function dataValidation ($receivedLogin, $receivedPassword)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        $pdo = new PDO($dbh, $user, $pass);
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email'=> $receivedLogin]);
        $userData = $stmt->fetch();

        if (!empty($userData) && password_verify($receivedPassword, $userData['password'])) {

            if (!isset($_SESSION['authorization'])) {
                $_SESSION['authorization'] = true;
            }

            setcookie('login', $userData['email'], time() + 86400 * 30, '/'); // 30 days
            $_SESSION['userId'] = $userData['id'];

            $pdo = null;
            $stmt = null;
            return true;
        }
        $pdo = null;
        $stmt = null;
        return false;
    }
    catch(PDOException $err) {
        echo $err->getMessage();
    }
}
