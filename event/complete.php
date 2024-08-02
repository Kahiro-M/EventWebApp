<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
require_once(ROOT.'/common/member.php'); 
$htmlTitle="イベント情報入力 | 完了";
$adminOnly = false;
$cssList=[
    '../css/navbar.css',
];
$jsList=['../js/navbar.js'];
require_once(ROOT.'/template/login_collect.php');
require_once(ROOT.'/event/validate.php');
require_once(ROOT.'/event/helper.php');

// POSTされた値の整形
$postData = [];
foreach($_POST as $key=>$value){
    $postData[$key] = h($value);
}

if(isset($_SESSION['uuid']) && isset($postData['uuid']) && $_SESSION['uuid']==$postData['uuid']){
    // UUID一致なので処理実行
        
    // POSTされた値の整形
    $postData = [];
    foreach($_POST as $key=>$value){
        $postData[$key] = h($value);
    }
    $postData = eventPostDataNormalize($postData);

    // バリデーションチェック
    $errMsg = [];
    $validateRet = eventValidate($postData,$errMsg);
    $postData = $validateRet['postData'];
    $errMsg = $validateRet['errMsg'];

    // 新規登録か更新か判断
    $isUpdate = isUpdate($postData);
    
    // 社員一覧取得
    $staffCodeNameList = getUserIdNameList();

    if(empty($errMsg)){
        $db = connect_db();

        // 作成日時と更新日時のカラム値文字列設定
        date_default_timezone_set('Asia/Tokyo');
        $timestamp = time();
        $now = date("Y-m-d H:i:s", $timestamp);
        
        // SQL文発行
        if($isUpdate){
            // DBのSETデータ文字列設定
            $setStrList = [];
            foreach(ITEM_LIST as $columns=>$title){
                if(isset($postData[$columns])){
                    $setStrList[$columns] = '`'.$columns.'`='.$db->quote($postData[$columns]);
                }
            }
            // 更新者
            $setStrList['updated_user'] = "`updated_user` = ".$db->quote($_SESSION['login_id']);
            // 更新者名
            $setStrList['updated_user_name'] = "`updated_user_name` = ".$db->quote($staffCodeNameList[$_SESSION['login_id']]);
            // 更新日時
            $setStrList['updated'] = "`updated` = ".$db->quote($now);
            // SETデータ文字列を生成
            $setStr = implode(',',array_values($setStrList));

            $id = $db->quote($postData['id']);
            $sql = "UPDATE `".EVENT_TABLE_STR."` SET $setStr WHERE `".EVENT_TABLE_STR."`.`id` = $id";
        }else{
            // DBのカラム名文字列設定
            $insertKeysStr = implode('`,`',array_keys(ITEM_LIST));

            // DBのカラム値文字列設定
            $insertValues = [];
            foreach(ITEM_LIST as $columns=>$title){
                if(isset($postData[$columns])){
                    $insertValues[$columns] = $postData[$columns];
                }else{
                    $insertValues[$columns] = '';
                }
            }
            $insertValuesStr = implode("','",array_values($insertValues));
            $sql ="INSERT INTO `".EVENT_TABLE_STR."` (`id`,`".$insertKeysStr."`,`created_user`,`updated_user`,`created_user_name`,`updated_user_name`,`created`,`updated`) VALUES(NULL,'".$insertValuesStr."',".$db->quote($_SESSION['login_id']).",".$db->quote($_SESSION['login_id']).",".$db->quote($staffCodeNameList[$_SESSION['login_id']]).",".$db->quote($staffCodeNameList[$_SESSION['login_id']]).",".$db->quote($now).",".$db->quote($now).")";
        }

        // SQL実行
        try {  
            // トランザクションを開始する。オートコミットがオフになる。
            $db->beginTransaction();
            // SQL実行
            $db->exec($sql);
            // コミット
            writeLog($htmlTitle."
                SQL:
                ".$sql);
            $db->commit();
        } catch (Exception $e) {
            // SQL実行エラー時にロールバック
            $db->rollBack();
            dbg_dump($sql,'sql');
            dbg_dump($postData,'postData');
            $errMsg['event_commit'] = 'DB登録に失敗しました。お手数ですが、再度最初から登録を行ってください。' . $e->getMessage();
            writeLog($htmlTitle.'    "'.$errMsg['event_commit'].'"');
            dbg_dump($errMsg);
            exit();
        }
        unset($_SESSION['uuid']);
    }else{
        header("Location: ./confirm.php", true, 307);
        exit();
    }

}else{
    $errMsg['event_commit'] = '不正な画面遷移です。';
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

<?php showErrmMsg($errMsg) ?>

<?php if(DEBUG_MODE){ ?>
<div class="container dbg" style="border:1px dashed #666;">
    <div class="d-flex align-items-center p-3 my-3 text-white bg-cyan rounded shadow-sm text-center dbg-button">
        <div class="container lh-1">
            <h1 class="h6 mb-0 text-white lh-1">DEBUG</h1>
        </div>
    </div>
    <div class="dbg-detail d-none">
<?php
        print('<div class="container" style="border:1px dashed #666;">');
        dbg_dump($postData,'$postData');
        print('</div>');
        print('<div class="container" style="border:1px dashed #666;">');
        dbg_dump($_SESSION,'$_SESSION');
        print('</div>');
?>
    </div>
</div>
<?php } ?>

<?php if(empty($errMsg)){ ?>
<?php   if($isUpdate){ ?>
<div class="row mt-1 mb-3">
    <div class="col-12 text-center">
        更新完了しました。
    </div>
</div>
<div class="row">
    <div class="col-5">
    </div>
    <div class="col-2 text-center">
        <a href="./show_list.php" class="btn btn-secondary" role="button">一覧へ戻る</a>
    </div>
</div>
<?php   }else{ ?>
<div class="row mt-1 mb-3">
    <div class="col-12 text-center">
        登録完了しました。
    </div>
</div>
<div class="row">
    <div class="col-5 text-end">
        <a href="/menu.php" class="btn btn-secondary" role="button">TOPへ戻る</a>
    </div>
    <div class="col-2">
    </div>
    <div class="col-5">
        <a href="/event/input.php" class="btn btn-success" role="button">続けて入力する</a>
    </div>
</div>
<?php   } ?>
<?php }else{ ?>
<div class="row">
    <div class="col-5 text-end">
        <a href="/menu.php" class="btn btn-secondary" role="button">TOPへ戻る</a>
    </div>
    <div class="col-2">
    </div>
    <div class="col-5">
        <a href="/event/input.php" class="btn btn-success" role="button">入力ページへ戻る</a>
    </div>
</div>
<?php } ?>

</main>
<?php include(ROOT.'/template/footer.php'); ?>
