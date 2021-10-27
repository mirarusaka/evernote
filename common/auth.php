<?php
//セッションが空の場合、セッションを生成
if(!isset($_SESSION)){
    session_start();
}

//ログインしているか判定
function isLogin() {
    if (isset($_SESSION['user'])) {
        return true;
    }
        return false;
}

//ログインユーザ名を取得。文字が多い時は、...で省略
function getLoginUserName() {
    if (isset($_SESSION['user'])) {
        $name = $_SESSION['user']['name'];
        if (7 < mb_strlen($name)) {
            $name = mb_substr($name, 0, 7) . "...";
        }
        return $name;
    }
        return "";
}

//ログインユーザIDを取得
function getLoginUserId() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user']['id'];
    }
    return null;
}