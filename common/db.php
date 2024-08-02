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

    function connect_db($host=DB_HOST, $dbname=DB_NAME, $username=DB_USERNAME, $password=DB_PASSWORD){
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

function getUserIdNameList(){
    $db = connect_db();

    // ユーザリスト取得
    $query = "SELECT `".USER_LOGIN_ID_STR."`,`".USER_NAME_STR."` FROM `".USER_TABLE_STR."`";
    $results = $db->query($query);
    $staffList = [];
    while($row = db_fetch($results)){
        $staffList[$row[USER_LOGIN_ID_STR]] = $row[USER_NAME_STR];
    }
    return $staffList;
}

?>