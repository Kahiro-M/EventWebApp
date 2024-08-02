<?php

function showErrMsg($errMsg){
    if(isset($errMsg)){
        print($errMsg);
    }
}

function dbg_dump($val,$title=''){
    print('<pre>');
    if(!empty($title)){
        print($title.' : ');
    }
    var_dump($val);
    print('</pre>');
}

function refreshSession(){
    // エラーメッセージ削除
    if(isset($errMsg)){
        $errMsg = '';
    }
    //セッション変数をクリア
    $_SESSION = array();
    //クッキーに登録されているセッションidの情報を削除
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    //セッションを破棄
    session_regenerate_id(TRUE);
}

// 正の整数チェック
function isPosiInt($value) {
    return preg_match('/^[0-9]+$/',$value);
}

// 時刻チェック
function isTimeHHMM($value) {
    return preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/',$value);
}
function isTimeHHMMSS($value) {
    return preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',$value);
}

// htmlspecialcharsの省略
function h($str){
    if(empty($str)){
        return $str;
    }else{
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}
// htmlspecialchars_decodeの省略
function hd($str){
    return htmlspecialchars_decode($str, ENT_QUOTES);
}
function isLoggedIn(){
    return (isset($_SESSION['login']) && $_SESSION['login'] == 'login');
}

function getLoginInfo(){
    $loginInfo = array();
    if(isset($_SESSION['login']) && $_SESSION['login'] == 'login'){
        $loginInfo = $_SESSION;
        return $loginInfo;
    }else{
        return $loginInfo;
    }
}

// 新規登録か更新か判断
function isUpdate($postData){
    $isUpdate = FALSE;
    if(isset($postData['id']) 
        && isset($postData['is_update'])
        && !empty($postData['id']) 
        && in_array($postData['is_update'],['true','TRUE','True'])
        ){
        $isUpdate = TRUE;
    }
    return $isUpdate;
}

// UUUIDv4生成
function generateUUIDv4() {
    // 16進数で32桁のデータ生成
    $data = random_bytes(16);

    // UUIDv4のバリアント設定
    // xxxxxxxx-xxxx-4xxx-{8,9,a,b}xxx-xxxxxxxxxxxx

    // バージョンを設定 (4はUUIDv4を表す)
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

    // RFC 4122 バリアントを設定
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function showErrmMsg($errMsg){
    // エラーメッセージが存在する場合は表示する
    if(!empty($errMsg)){
        print('    <div class="d-flex align-items-center p-3 my-3 text-white bg-red rounded shadow-sm">'."\n");
        print('        <div class="lh-1">'."\n");
        print('            <h1 class="h6 mb-0 text-white lh-1">'."\n");
        foreach($errMsg as $key => $msg){
            print('            <p class="mb-0">'.h($msg).'</p>'."\n");
        }
        print('            </h1>'."\n");
        print('        </div>'."\n");
        print('    </div>'."\n");
    }
}

function getLoginInfoForLogStr(){
    if(isset($_SESSION['user_name']) && isset($_SESSION['login_id']) && isset($_SESSION['role'])){
        return '['.$_SESSION['user_name'].'('.$_SESSION['login_id'].')'.$_SESSION['role'].']';
    }else{
        return '';
    }
}

// ログ保存
function writeLog($msg,$saveMsgOnly=false){
    $host=LOG_DB_HOST;
    $name=LOG_DB_NAME;
    $username=LOG_DB_USERNAME;
    $password=LOG_DB_PASSWORD;
    try {
        $log_db = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $username, $password);
        $log_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("エラー: " . $e->getMessage());
    }

    // 作成日時と更新日時のカラム値文字列設定
    date_default_timezone_set('Asia/Tokyo');
    $timestamp = time();
    $now = date("Y-m-d H:i:s", $timestamp);

    $sessionId = session_id();
    $userInfo = getLoginInfoForLogStr();
    $method = $_SERVER['REQUEST_METHOD'];

    if(empty($_POST)){
        $post = '';
    }else{
        ob_start();
        var_dump($_POST);
        $post = ob_get_clean();
    }
    
    if(empty($_GET)){
        $param = '';
    }else{
        ob_start();
        var_dump($_GET);
        $param = ob_get_clean();
    }

    if($saveMsgOnly){
        $sql ="INSERT INTO `".LOG_DB_TABLE."` (`id`,`datetime`,`session`,`user`,`method`,`param`,`post`,`message`) VALUES(NULL,".$log_db->quote($now).",".$log_db->quote($sessionId).",".$log_db->quote($userInfo).",'','','',".$log_db->quote($msg).")";
    }else{
        $sql ="INSERT INTO `".LOG_DB_TABLE."` (`id`,`datetime`,`session`,`user`,`method`,`param`,`post`,`message`) VALUES(NULL,".$log_db->quote($now).",".$log_db->quote($sessionId).",".$log_db->quote($userInfo).",".$log_db->quote($method).",".$log_db->quote($param).",".$log_db->quote($post).",".$log_db->quote($msg).")";
    }

    // SQL実行
    try {  
        // トランザクションを開始する。オートコミットがオフになる。
        $log_db->beginTransaction();
        // SQL実行
        $log_db->exec($sql);
        // コミット
        $log_db->commit();
    } catch (Exception $e) {
        // SQL実行エラー時にロールバック
        $log_db->rollBack();
        dbg_dump($sql,'sql');
        $errMsg['forUser'] = 'LOG DB登録に失敗しました。お手数ですが、再度最初から登録を行ってください。';
        $errMsg['forAdmin'] = $e->getMessage();
        dbg_dump($errMsg);
        dbg_dump($post,'$post');
        dbg_dump($param,'$param');
        dbg_dump($sql,'$sql');
        exit();
    }

}

// csv生成（$dataは多次元配列）
function dlCsv($header,$data,$fileName='tmp.csv',$utf8=false,$encBom=false) {
    try {
        // ダウンロード開始
        // ファイルタイプ（csv）
        header('Content-Type: application/octet-stream');
        // ファイル名
        header('Content-Disposition: attachment; filename=' . $fileName); 
        // ファイルのサイズ　ダウンロードの進捗状況が表示
        header('Content-Transfer-Encoding: binary');

        $csv = fopen('php://output', 'w');
        if ($csv === FALSE) {
            throw new Exception('ファイルの書き込みに失敗しました。');
        }
        
        if($utf8 && $encBom){
            // UTF8 BOM付きにする
            fwrite($csv, "\xEF\xBB\xBF");
        }

        // 項目名先に出力
        if($utf8 == false){
            mb_convert_variables('SJIS', 'UTF-8', $header);
        }
        fputcsv($csv, $header);

        // ループしながら出力
        foreach($data as $dataInfo) {
            // 文字コード変換
            if($utf8 != true){
                mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
            }

            // ファイルに書き出しをする
            fputcsv($csv, $dataInfo);
        }

        // ファイルを閉じる
        fclose($csv);

    } catch(Exception $e) {
        // 例外処理
        echo $e->getMessage();
    }
}

?>
