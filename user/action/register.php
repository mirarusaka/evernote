<?php
    //ファイルをインポート
    require '../../common/database.php';

    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    //インポートしたメソッドを代入
    $database_handler = getDatabaseConnection();

    try {
        if ($statement = $database_handler->prepare('INSERT INTO users(
            name, email, password) VALUES (:name, :email, :password)')) {
            $password = password_hash($user_password, PASSWORD_DEFAULT);
            $statement->bindParam(':name', htmlspecialchars($user_name));
            $statement->bindParam(':email', $user_email);
            $statement->bindParam(':password', $password);
            $statement->execute();
        }
    } catch (Throwable $e) {
        echo $e->getMessage();
        exit;
    }
    header('Location: ../../memo/');
    exit;