<?php
    //セッションを生成
    session_start();

    //ファイルをインポート
    require '../../common/database.php';
    require '../../common/validation.php';

    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    //エラーメッセージを格納する領域をセッション内に生成
    $_SESSION['errors'] = [];

    //エラーメッセージを格納
    emptyCheck($_SESSION['errors'], $user_name, "ユーザー名を⼊⼒してください。");
    emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを⼊⼒してください。");
    emptyCheck($_SESSION['errors'], $user_password, "パスワードを⼊⼒してください。");

    stringMaxSizeCheck($_SESSION['errors'], $user_name, "ユーザー名は255⽂字以内で⼊⼒してください。");
    stringMaxSizeCheck($_SESSION['errors'], $user_email, "メールアドレスは255⽂字以内で⼊⼒してください。");
    stringMaxSizeCheck($_SESSION['errors'], $user_password, "パスワードは255⽂字以内で⼊⼒してください。");
    stringMinSizeCheck($_SESSION['errors'], $user_password, "パスワードは8⽂字以上で⼊⼒してください。");

    //既にバリデーションに引っかかっている場合、以下のチェックはしない
    //エラーメッセージが重複しないようにするため
    if(!$_SESSION['errors']) {
        mailAddressCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを⼊⼒してください。");
        halfAlphanumericCheck($_SESSION['errors'], $user_name, "ユーザー名は半⾓英数字で⼊⼒してください。");
        halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半⾓英数字で⼊⼒してください。");
        mailAddressDuplicationCheck($_SESSION['errors'], $user_email, "既に登録されているメールアドレスです。");
    }

    //バリデーションエラーがある場合、入力フォームへリダイレクト
    if($_SESSION['errors']) {
        header('Location: ../../user/');
        exit;
    }

    //インポートしたメソッドを代入
    $database_handler = getDatabaseConnection();

    try { //SQL文を格納。失敗したら例外発生
        if ($statement = $database_handler->prepare('INSERT INTO users(
            name, email, password) VALUES (:name, :email, :password)')) {

            //格納したSQL文にパラメータ(入力値)をセットしてexecuteでSQL文を実行
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
    //リダイレクト先を設定
    header('Location: ../../memo/');
    exit;