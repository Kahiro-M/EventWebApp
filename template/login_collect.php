<?php
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION['login']) || $_SESSION['login'] != 'login') {
  refreshSession();
  $_SESSION['err'] = 'ログインしてください。'; // セッションにエラーメッセージを表示
  session_write_close();
  header("Location: http://". $_SERVER["HTTP_HOST"] ."/logout.php");
  exit();
}

?>
