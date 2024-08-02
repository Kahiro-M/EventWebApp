<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
$htmlTitle="イベント情報照会 | Download";
$adminOnly = false;
require_once(ROOT.'/template/login_collect.php');
require_once(ROOT.'/event/validate.php');
require_once(ROOT.'/event/helper.php');

// DB接続
$db = connect_db();

$param = $_GET;
$postData = $_POST;

// download対象のIDが無い場合は一覧画面へ戻す
if(!isset($postData['dl_id'])){
    header("Location:./show_list.php");
    exit();
}

// download形式の指定が無い場合は一覧画面へ戻す
if(!isset($param['csv_dl']) || !in_array($param['csv_dl'],array_values(DOWNLOAD_FORMAT))){
    header("Location:./show_list.php");
    exit();
}


// IDをエスケープされた値にする
$quotedDlIdList = [];
foreach($postData['dl_id'] as $key=>$id){
    $quotedDlIdList[] = $db->quote($id);
}
$dlIdStr = implode(',',$quotedDlIdList);

// ID指定が不正な場合は強制ログアウト
if(!preg_match('/^\'\d+\'(,\'\d+\')*$/',$dlIdStr)){
    exit('NG!!!');
    header("Location: http://". $_SERVER["HTTP_HOST"] ."/logout.php");
    exit();
}

// SQL文発行
$sql ="SELECT * FROM `".EVENT_TABLE_STR."` WHERE `id` in ($dlIdStr)";
$ret = $db->query($sql);
writeLog($htmlTitle."
    SQL:
    ".$sql);
$searchRet = array();
while ($row = db_fetch($ret)) {
    $searchRet[$row['id']] = $row;
}

// 作成日時と更新日時のカラム値文字列設定
date_default_timezone_set('Asia/Tokyo');
$timestamp = time();
$now = date("Ymd_His", $timestamp);

// URLパラメータに応じてcsvダウンロード
if($param['csv_dl'] == DOWNLOAD_FORMAT['salesforce']){
    // Salesforce用にデータ整形
    $outputData = [];
    foreach($searchRet as $id => $data){
        foreach(FORM_TO_SALESFORCE as $itemName => $labelStr){
            if(isset($data[$itemName])){
                if(in_array($itemName,['start_time','end_time'])){
                    // HH:MM:SS→HH:MMに整形
                    $outputData[$id][$itemName] = substr($data[$itemName],0,5);
                }else if(in_array($itemName,['member_code'])){
                    if(empty($data[$itemName])){
                        $outputData[$id][$itemName] = '';
                    }else{
                        // 会員番号は0埋め6桁
                        $outputData[$id][$itemName] = sprintf('%06d',$data[$itemName]);
                    }
                }else{
                    $outputData[$id][$itemName] = $data[$itemName];
                }
            }else{
                $outputData[$id][$itemName] = '';
            }
        }
    }

    // csv出力のヘッダー生成
    $label = array_values(FORM_TO_SALESFORCE);

    // csv出力
    dlCsv($label,$outputData,'salesforce_campain_data_'.$now.'.csv');
}else if($param['csv_dl'] == DOWNLOAD_FORMAT['general']){
    // 一覧用にデータ整形
    $outputData = [];
    foreach($searchRet as $id => $data){
        foreach(ITEM_LIST as $itemName => $labelStr){
            if(isset($data[$itemName])){
                $outputData[$id][$itemName] = $data[$itemName];
            }
        }
    }
    
    // csv出力のヘッダー生成
    $label = array_values(ITEM_LIST);
    // csv出力
    dlCsv($label,$outputData,'campain_data_'.$now.'.csv');
}


exit();
