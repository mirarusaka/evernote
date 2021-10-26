<?php
function getDatabaseConnection() {
    try{
        //ここでDBアクセス開始処理を行っている。PDOオブジェクトに、DBテーブル名、ユーザ名、パスワードを渡すことでアクセス可能
        $database_handler = new
        PDO('mysql:host=db;dbname=simple_memo;charset=utf8mb4', 'root',
        'password');
    }
    catch (PDOException $e){
        echo "DB接続に失敗しました。<br />";
        echo $e->getMessage();
        exit;
    }
    return $database_handler;
}