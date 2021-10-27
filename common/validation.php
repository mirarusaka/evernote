<?php
//errorsは参照渡しで代入。戻り値設定しない。

//trim関数は、空白を消してくれる。emptyでその結果が空か判定。
function emptyCheck(&$errors, $check_value, $message){
    if (empty(trim($check_value))) {
        array_push($errors, $message);
    }
}

//文字数が最小値より小さいかチェック
function stringMinSizeCheck(&$errors, $check_value, $message, $min_size = 8){
    if (mb_strlen($check_value) < $min_size) {
        array_push($errors, $message);
    }
}

//文字数が最大値より大きいかチェック
function stringMaxSizeCheck(&$errors, $check_value, $message, $max_size = 255) {
    if ($max_size < mb_strlen($check_value)) {
            array_push($errors, $message);
    }
}

//メールアドレスの形式かチェック
function mailAddressCheck(&$errors, $check_value, $message) {
    if (filter_var($check_value, FILTER_VALIDATE_EMAIL) == false) {
        array_push($errors, $message);
    }
}

//半角文字だけかチェック preg_matchで正規表現に適合しているか判定している
function halfAlphanumericCheck(&$errors, $check_value, $message) {
    if (preg_match("/^[a-zA-Z0-9]+$/", $check_value) == false) {
        array_push($errors, $message);
    }
}

//登録済メールアドレスかチェック
function mailAddressDuplicationCheck(&$errors, $check_value, $message) {
    //DB接続
    $database_handler = getDatabaseConnection();

    //入力したメールアドレスで検索
    if ($statement = $database_handler->prepare('SELECT id FROM users WHERE email = :user_email')) {
        $statement->bindParam(':user_email', $check_value);
        $statement->execute();
    }

    //結果を格納
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    //値が0(=false)ではない場合、重複なのでエラー
    if ($result) {
        array_push($errors, $message);
    }
}