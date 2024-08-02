<?php
// SQLiteとMySQLでの接続切替
if(DB_MODE == 'SQLite'){
    class MyDB extends SQLite3
    {
        function __construct($dbFilePath)
        {
            $this->open($dbFilePath);
        }
    }
    $db = new MyDB($dbFilePath);
}else{
    // DB接続
    function connect_db($host=LOG_DB_HOST, $dbname=LOG_DB_NAME, $username=LOG_DB_USERNAME, $password=LOG_DB_PASSWORD){
        try {
            $db_tmp = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $db_tmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db_tmp;
        } catch (PDOException $e) {
            die("エラー: " . $e->getMessage());
        }
    }
    $db = connect_db();
}

// SQLiteのfetchArray()とMySQLのfetch()のラッパー関数
function db_fetch($results){
    if(DB_MODE == 'SQLite'){
        return $results->fetchArray();
    }else{
        return $results->fetch();
    }
}

// SQLiteでのレコード数計算とMySQLのrowCount()のラッパー関数
function db_count($results){
    if(DB_MODE == 'SQLite'){
        $count = 0;
        while ($row = db_fetch($results)) {
            $count++;
        }
        $results->reset();
        return $count;
    }else{
        return $results->rowCount();
    }
}

?>