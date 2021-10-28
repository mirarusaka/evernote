<?php
    session_start();
    $_SESSION = [];

    //セッションを破棄してログイン情報を消す
    session_destroy();
    header('Location: ../../login/');
    exit;