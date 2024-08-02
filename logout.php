<?php
session_start();
require_once('config/env.php'); 
require_once(ROOT.'/common/common.php'); 

writeLog('ログアウト試行');
if(!empty($_SESSION)){
    $tmp = $_SESSION;
}

// セッション変数を全て解除する
$_SESSION = array();
 
// セッションを切断するにはセッションクッキーも削除する。
// Note: セッション情報だけでなくセッションを破壊する。
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
 
// 最終的に、セッションを破壊する
session_destroy();
if(!empty($_SESSION)){
    writeLog('ログアウト('.$tmp['user_name'].' '.$tmp['login_id'].')');
}else{
    writeLog('ログアウト');
}

// ログアウト後のリダイレクト処理
header("Location: http://". $_SERVER["HTTP_HOST"] ."/index.php");
exit();
?>