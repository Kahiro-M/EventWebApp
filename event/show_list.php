<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
require_once(ROOT.'/common/member.php'); 
$htmlTitle="イベント情報照会";
$adminOnly = false;
$cssList=[
    '../css/navbar.css',
    '../css/event/show_list.css',
];
$jsList=[
    '../js/navbar.js',
    '../js/event/show_list.js',
];
require_once(ROOT.'/template/login_collect.php');
require_once(ROOT.'/event/validate.php');
require_once(ROOT.'/event/helper.php');

// POSTされた値の整形
$postData = [];
foreach($_POST as $key=>$value){
    $postData[$key] = h($value);
}
if(empty($postData['event_name'])){
    $postData['event_name'] = '';
}
if(empty($postData['event_type'])){
    $postData['event_type'] = '';
}
if(empty($postData['member_code'])){
    $postData['member_code'] = '';
}else{
    $postData['member_code'] = intval($postData['member_code']);
}
if(empty($postData['member_code_null'])){
    $postData['member_code_null'] = '';
}else if(!in_array($postData['member_code_null'],['true','false'])){
    $postData['member_code_null'] = 'false';
}else{
    // NULL指定の場合は会員番号を消去する
    $postData['member_code'] = '';
}
if(empty($postData['pref_name'])){
    $postData['pref_name'] = '';
}
if(empty($postData['event_date_start'])){
    $postData['event_date_start'] = '';
}
if(empty($postData['event_date_end'])){
    $postData['event_date_end'] = '';
}
if(empty($postData['updated_start'])){
    $postData['updated_start'] = '';
}
if(empty($postData['updated_end'])){
    $postData['updated_end'] = '';
}

// バリデーション
$errMsg = [];
if(!in_array($postData['event_type'],array_values(EVENT_TYPE))){
    $errMsg['event_type'] = 'イベント種別は選択肢から選んでください。';
    $postData['event_type'] = '';
}
if(!in_array($postData['pref_name'],array_values(PREF_CODE_TO_PREF_NAME))){
    $errMsg['pref_name'] = '都道府県は選択肢から選んでください。';
    $postData['pref_name'] = '';
}


// DB接続
$db = connect_db();

// SQL文発行
$sql ="SELECT * FROM `".EVENT_TABLE_STR."`";
$whereStr = " WHERE ";
$whereStr .= "`event_name` LIKE ".$db->quote('%'.$postData['event_name'].'%');
if(!empty($postData['event_type'])){
    $whereStr .= " AND ";
    $whereStr .= "`event_type` LIKE ".$db->quote($postData['event_type']);
}
if(!empty($postData['member_code_null'])){
    $whereStr .= " AND ";
    $whereStr .= "`member_code` LIKE ''";
}
if(!empty($postData['member_code']) && empty($postData['member_code_null'])){
    $whereStr .= " AND ";
    $whereStr .= "`member_code` LIKE ".$db->quote('%'.$postData['member_code'].'%');
}
if(!empty($postData['pref_name'])){
    $whereStr .= " AND ";
    $whereStr .= "`pref_name` LIKE ".$db->quote($postData['pref_name']);
}
if(!empty($postData['event_date_start'])){
    $whereStr .= " AND ";
    $whereStr .= "`event_date` >= ".$db->quote($postData['event_date_start']);
}
if(!empty($postData['event_date_end'])){
    $whereStr .= " AND ";
    $whereStr .= "`event_date` <= ".$db->quote($postData['event_date_end']);
}
if(!empty($postData['updated_start'])){
    $whereStr .= " AND ";
    $whereStr .= "`updated` >= ".$db->quote($postData['updated_start']);
}
if(!empty($postData['updated_end'])){
    $whereStr .= " AND ";
    $whereStr .= "`updated` <= ".$db->quote($postData['updated_end']);
}
$ret = $db->query($sql.$whereStr);
writeLog($htmlTitle."
    SQL:"
    .$sql.$whereStr);
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
                            イベント名
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="event_name" value="<?php print($postData['event_name']); ?>">
                        </div>
                        
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            イベント種別
                        </div>

                        <div class="col-sm-4">
                            <?php makeSelectEventType('event_type',$postData,FALSE,[''=>'---']) ?>
                        </div>
                    </div>
                    <div class="row mt-1">

                        <div class="label mt-2 col-sm-2 text-sm-end">
                            会員番号
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" type="text" name="member_code" value="<?php print($postData['member_code']); ?>">
                        </div>
                        <div class="label mt-1 col-sm-1 text-sm-end">
                            なし
                        </div>
                        <div class="col-sm-1 mt-1">
                            <div class="d-flex justify-content-center input form-check form-switch">
                                <input class="form-check-input member-code-null-checkbox" type="checkbox" role="switch" id="member_code_null" name="member_code_null" value="true" <?php if(isset($postData['member_code_null']) && $postData['member_code_null'] == 'true'){print('checked');} ?>>
                            </div>
                        </div>
                        
                        <div class="label mt-2 col-sm-2 text-sm-end">
                            都道府県
                        </div>

                        <div class="col-sm-4">
                            <?php makeSelectPrefName('pref_name',$postData,FALSE,FALSE,[''=>'---']) ?>
                        </div>
                    </div>
                    <div class="row mt-1">

                        <div class="label mt-2 col-sm-2 text-sm-end">
                            開催日
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control label-start" type="date" name="event_date_start" value="<?php print($postData['event_date_start']); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control label-end" type="date" name="event_date_end" value="<?php print($postData['event_date_end']); ?>">
                        </div>

                        <div class="label mt-2 col-sm-2 text-sm-end">
                            更新日時
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control label-start" type="datetime-local" name="updated_start" value="<?php print($postData['updated_start']); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control label-end" type="datetime-local" name="updated_end" value="<?php print($postData['updated_end']); ?>">
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
            <a href="./data_to_Salesforce.sdl" download="data_to_Salesforce.sdl" class="btn btn-outline-secondary" role="button">Dataloader用Mappingファイル <img class="svg" src="/img/download_icon.svg"></a>
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
                    <th scope="col" class="text-nowrap text-center"><img class="svg" src="/img/download_icon.svg"></th>
                    <th scope="col" class="text-nowrap text-center">No.</th>
                    <th scope="col" class="text-nowrap text-center">会員<br class="d-sm-none">番号</th>
                    <th scope="col" class="text-center">主催者</th>
                    <th scope="col" class="text-center">イベント名</th>
                    <th scope="col" class="text-nowrap text-center">イベント<br class="d-sm-none">種別</th>
                    <th scope="col" class="text-nowrap text-center">ブロック</th>
                    <th scope="col" class="text-nowrap text-center">都道府県</th>
                    <th scope="col" class="text-nowrap text-center">開催日</th>
                    <th scope="col" class="text-nowrap text-center">開催<br class="d-sm-none">時刻</th>
                    <th scope="col" class="text-center">更新<br class="d-sm-none">日時</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($searchRet as $num => $data){ ?>
                <tr>
                    <td class="align-middle">
                        <div class="d-flex justify-content-center input form-check form-switch">
                            <input class="form-check-input dl-checkbox" type="checkbox" role="switch" id="dl_id_<?= $data['id'] ?>" name="dl_id[]"  value="<?= $data['id'] ?>">
                        </div>
                    </td>
                    <td class="align-middle text-nowrap text-center" scope="row">
                        <a class="show-detail-icon" role="button" href="./show_detail.php?id=<?= $data['id'] ?>"><img src="/img/pageview_icon.svg" alt="詳細ページへ" title="詳細ページへ"></a>
                        <?= $data['id'] ?>
                    </td>
                    <td class="align-middle">
<?php if(!empty($data['member_code'])){ ?>
                            <?= sprintf('%06d',$data['member_code']) ?>
<?php } ?>
                    </td>
                    <td class="align-middle"><?= $data['organizer_name'] ?></td>
                    <td class="align-middle"><?= $data['event_name'] ?></td>
                    <td class="align-middle"><?= $data['event_type'] ?></td>
                    <td class="align-middle text-nowrap"><?= $data['area_block'] ?></td>
                    <td class="align-middle text-nowrap"><?= $data['pref_name'] ?></td>
                    <td class="align-middle text-nowrap"><?= $data['event_date'] ?></td>
                    <td class="align-middle"><?= substr($data['start_time'],0,5) ?></td>
                    <td class="align-middle"><?= substr($data['updated'],0,16) ?></td>
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
