<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/log_db.php'); 
require_once(ROOT.'/common/common.php'); 
$htmlTitle="ログ参照";
$adminOnly = true;
$cssList=[
    '../css/navbar.css',
    '../css/log/show_list.css',
];
$jsList=[
    '../js/navbar.js',
    '../js/log/show_list.js',
];
require_once(ROOT.'/template/login_collect.php');
require_once(ROOT.'/event/validate.php');
require_once(ROOT.'/event/helper.php');

// POSTされた値の整形
$postData = [];
foreach($_POST as $key=>$value){
    $postData[$key] = h($value);
}
if(empty($postData['user'])){
    $postData['user'] = '';
}
if(empty($postData['session'])){
    $postData['session'] = '';
}
if(empty($postData['method'])){
    $postData['method'] = '';
}
if(empty($postData['message'])){
    $postData['message'] = '';
}
if(empty($postData['datetime_start'])){
    $postData['datetime_start'] = '';
}
if(empty($postData['datetime_end'])){
    $postData['datetime_end'] = '';
}

// バリデーション
$errMsg = [];

// DB接続
$db = connect_db();

// SQL文発行
$sql ="SELECT * FROM `".LOG_DB_TABLE."`";
$whereStr = " WHERE ";
$whereStr .= "`user` LIKE ".$db->quote('%'.$postData['user'].'%');
if(!empty($postData['method'])){
    $whereStr .= " AND ";
    $whereStr .= "`method` LIKE ".$db->quote('%'.$postData['method'].'%');
}
if(!empty($postData['session'])){
    $whereStr .= " AND ";
    $whereStr .= "`session` LIKE ".$db->quote('%'.$postData['session'].'%');
}
if(!empty($postData['message'])){
    $whereStr .= " AND ";
    $whereStr .= "`message` LIKE ".$db->quote('%'.$postData['message'].'%');
}
if(!empty($postData['datetime_start'])){
    $whereStr .= " AND ";
    $whereStr .= "`datetime` >= ".$db->quote($postData['datetime_start']);
}
if(!empty($postData['datetime_end'])){
    $whereStr .= " AND ";
    $whereStr .= "`datetime` <= ".$db->quote($postData['datetime_end']);
}
$orderStr = "  ORDER BY ".LOG_DB_TABLE.".`id` DESC";
$ret = $db->query($sql.$whereStr.$orderStr);
// writeLog($htmlTitle."
//     SQL:
//     ".$sql.$whereStr);
$searchRet = array();
while ($row = db_fetch($ret)) {
    $searchRet[$row['id']] = $row;
}

include(ROOT.'/template/header.php');
include(ROOT.'/template/navbar.php');

?>

<main class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white bg-teal rounded shadow-sm text-center">
        <div class="container lh-1">
            <h1 class="h6 mb-0 text-white lh-1"><?php print($htmlTitle); ?></h1>
        </div>
    </div>
    <div class="search-container d-flex align-items-center pt-3 pb-1 my-3 bg-whitesmoke border rounded shadow-sm">
        <div class="container text-gray">
            <div class="search-title row lh-1 text-center">
                <h1 class="h6 mb-3 lh-1">検索条件</h1>
            </div>
            <div class="search-detail mb-0" <?php if(empty($_POST)){print('style="display:none;"');} ?>>
                <form method="POST" name="event_search" action="./show_list.php">
                    <div class="row mt-1">
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            ユーザ
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="user" value="<?php print($postData['user']); ?>">
                        </div>
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            セッション
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="session" value="<?php print($postData['session']); ?>">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            メソッド
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="method" value="<?php print($postData['method']); ?>">
                        </div>
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            ログ
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="message" value="<?php print($postData['message']); ?>">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            日時
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control label-start" type="datetime-local" name="datetime_start" value="<?php print($postData['datetime_start']); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control label-end" type="datetime-local" name="datetime_end" value="<?php print($postData['datetime_end']); ?>">
                        </div>
                    </div>
                    <div class="row mt-1 mb-0">
                        <div class="col-sm-6 text-center text-sm-end">
                            <button class="btn btn-secondary reset-button" type="button">リセット</button>
                        </div>
                        <div class="col-sm-6 text-center text-sm-start">
                            <button class="btn btn-outline-success" type="submit">検索</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<form method="POST" name="event_csv" action="?">
    <div class="row mt-3 csv-download-button" style="display:none;">
        <div class="col-sm-9 mt-2 text-center">
            <button class="btn btn-outline-success" type="submit" formaction="./csv_download.php?csv_dl=<?= DOWNLOAD_FORMAT['salesforce'] ?>">Salesforce用CSV <img class="svg" src="/img/download_icon.svg"></button>
            <a href="./data_to_Salesforce.sdl" class="btn btn-outline-secondary" role="button">Dataloader用Mappingファイル <img class="svg" src="/img/download_icon.svg"></a>
        </div>
        <div class="col-sm-3 mt-2 text-center">
            <button class="btn btn-outline-info" type="submit" formaction="./csv_download.php?csv_dl=<?= DOWNLOAD_FORMAT['general'] ?>">情報一覧 <img class="svg" src="/img/download_icon.svg"></button>
        </div>
    </div>
    <div class="row">
        <div class="col text-end">
            <?= count($searchRet) ?>件
        </div>
    </div>
    <div class="table-responsive">
        <table class="search-ret-list table table-bordered table-striped">
            <thead class="table-primary position-sticky">
                <tr>
                    <th scope="col" class="text-center">No.</th>
                    <th scope="col" class="text-center">日時</th>
                    <th scope="col" class="text-center">セッション</th>
                    <th scope="col" class="text-center">ユーザ</th>
                    <th scope="col" class="text-center">メソッド</th>
                    <th scope="col" class="">ログ</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($searchRet as $num => $data){ ?>
                <tr>
                    <td class="align-middle text-center" scope="row">
                        <a class="show-detail-icon" role="button" href="./show_detail.php?id=<?= $data['id'] ?>"><img src="/img/pageview_icon.svg" alt="詳細ページへ" title="詳細ページへ"></a>
                        <?= $data['id'] ?>
                    </td>
                    <td class="align-middle"><div style="width:85px;"><?= $data['datetime'] ?></div></td>
                    <td class="align-middle"><div style="width:100px;" class="text-truncate"><?= $data['session'] ?></div></td>
                    <td class="align-middle"><div style="width:85px;"><?= str_replace(['(',')'],'<br>',str_replace(['[',']'],'',$data['user'])) ?></div></td>
                    <td class="align-middle"><div style="width:50px;" class="text-truncate"><?= $data['method'] ?></div></td>
                    <td class="align-middle">
<?php
    $msg = [
        substr($data['message'],0,100),
        substr($data['message'],101,100),
        substr($data['message'],201,100),
        substr($data['message'],301,5),
    ];
    if(empty($msg[0])){
        print('');
    }else if(empty($msg[1])){
        print(implode('<br>',array_slice($msg,0,1)));
    }else if(empty($msg[2])){
        print(implode('<br>',array_slice($msg,0,2)));
    }else if(empty($msg[3])){
        print(implode('<br>',array_slice($msg,0,3)));
    }else{
        print(implode('<br>',array_slice($msg,0,3)).'...');
    }
?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php //makeSubmitButton('Salesforce用CSV Download','csv_dl[]','salesforce','outline-success'); ?>
    <?php //makeSubmitButton('CSV Download','csv_dl[]','general','outline-success'); ?>
</form>



<?php //showErrmMsg($errMsg) ?>

</main>
<?php include(ROOT.'/template/footer.php'); ?>
