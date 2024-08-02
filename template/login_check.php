<?php
session_start();

//ログイン状態の場合ログイン後のページにリダイレクト
if (isset($_SESSION['login'])) {
    session_regenerate_id(TRUE);
    header("Location: menu.php");
    exit();
}

// ユーザ認証
// フォームから送信されたデータを取得

if ($_SERVER['REQUEST_METHOD'] == 'POST'
    && isset($_POST['login_id'])
    && isset($_POST['password'])
    ) {
    $login_id = htmlspecialchars($_POST['login_id'],ENT_QUOTES,'UTF-8');
    $password = htmlspecialchars($_POST['password'],ENT_QUOTES,'UTF-8');

    // ユーザーが存在するかを確認
    $query = "SELECT * FROM `".USER_TABLE_STR."` WHERE `".USER_LOGIN_ID_STR."` LIKE '$login_id'";
    $results = $db->query($query);
    $user = db_fetch($results);
    
    if(isset($adminOnly) && $adminOnly == true){
        $allowAdmin = $user['admin']===ROLE_ADMIN;
    }else{
        $allowAdmin = true;
    }

    // パスワードを確認
    if (!($user) || !(password_verify($password, $user[USER_PASSWORD_STR])) || !($allowAdmin)) {
        // IDパスワードが不一致の場合

        // セッションを初期化
        refreshSession();

        // セッションにエラーメッセージを表示
        $_SESSION['err'] = 'IDとパスワードが不一致';

        writeLog('ログイン失敗'."
            SQL:
            ".$query);

        //ログイン後のページにリダイレクト
        header("Location: index.php");
        exit('ID is not find.');
    }else{

        // セッションを初期化
        refreshSession();

        // セッションにログイン情報を登録
        $_SESSION['login'] = 'login';
        $_SESSION['login_id'] = $user[USER_LOGIN_ID_STR];
        $_SESSION['user_name'] = $user[USER_NAME_STR];
        $_SESSION['admin'] = $user[USER_ADMIN_STR];
        $_SESSION['role'] = USER_ROLE[$user[USER_ADMIN_STR]];

        writeLog('ログイン成功',true);

        header("Location: menu.php"); //ログイン後のページにリダイレクト
    }
}else{
    // セッションにエラーメッセージがある場合は変数に格納して、消去。
    if(isset($_SESSION['err'])){
        $errMsg = $_SESSION['err'];
        $_SESSION = array();
        session_regenerate_id(TRUE);
    }
}

?>
