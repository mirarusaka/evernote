<?php
    session_start();
    require '../../common/validation.php';
    require '../../common/database.php';

    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $_SESSION['errors'] = [];

    emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを⼊⼒してください。");
    emptyCheck($_SESSION['errors'], $user_password, "パスワードを⼊⼒してください。");

    stringMaxSizeCheck($_SESSION['errors'], $user_email, "メールアドレスは255⽂字以内で⼊⼒してください。");
    stringMaxSizeCheck($_SESSION['errors'], $user_password, "パスワードは255⽂字以内で⼊⼒してください。");
    stringMinSizeCheck($_SESSION['errors'], $user_password, "パスワードは8⽂字以上で⼊⼒してください。");

    if(!$_SESSION['errors']) {
        mailAddressCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを⼊⼒してください。");
        halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半⾓英数字で⼊⼒してください。");
    }
    if($_SESSION['errors']) {
        header('Location: ../../login/');
        exit;
    }

    $database_handler = getDatabaseConnection();
    
    if ($statement = $database_handler->prepare('SELECT id, name, password FROM users WHERE email = :user_email')) {
        $statement->bindParam(':user_email', $user_email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $_SESSION['errors'] = ['メールアドレスまたはパスワードが間違っています。'];
            header('Location: ../../login/');
            exit;
        }
        $name = $user['name'];
        $id = $user['id'];
        if (password_verify($user_password, $user['password'])) {
            $_SESSION['user'] = [
            'name' => $name,
            'id' => $id
            ];

            //ログインし、ログイン者情報からメモを取得。最新の1件を取得(DESC LIMIT 1がそれ)
            if ($statement = $database_handler->prepare("SELECT id, title, content
            FROM memos WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT 1")) {
                $statement->bindParam(":user_id", $id);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $_SESSION['select_memo'] = [
                        'id' => $result['id'],
                        'title' => $result['title'],
                        'content' => $result['content']
                    ];
                }
            }

            header('Location: ../../memo/');
            exit;
        } else {
            $_SESSION['errors'] = ['メールアドレスまたはパスワードが間違っています。'];
            header('Location: ../../login/');
            exit;
        }
    }
?>