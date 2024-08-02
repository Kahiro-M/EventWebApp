<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
require_once(ROOT.'/common/member.php'); 
$htmlTitle="イベント情報入力 | 確認";
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

// セッションのUUIDとPOSTのUUIDが不一致、どちらか一方が無い場合は不正な画面遷移なので入力ページに戻す
if(isset($_SESSION['uuid']) && isset($postData['uuid'])){
    if($_SESSION['uuid'] != isset($postData['uuid'])){
        header("Location: ./input.php");
        exit();
    }
}else{
    header("Location: ./input.php");
    exit();
}

$postData = eventPostDataNormalize($postData);

// バリデーションチェック
$errMsg = [];
$validateRet = eventValidate($postData,$errMsg);
$postData = $validateRet['postData'];
$errMsg = $validateRet['errMsg'];

// 新規登録か更新か判断
$isUpdate = isUpdate($postData);

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

<?php
// 編集時はIDも表示
if($isUpdate){
    makeConfirmItem('ID','id',$postData,$errMsg);
}
// 入力項目の表示
foreach(ITEM_LIST as $itemName => $itemTitle){
    if(isset($postData[$itemName])){
        makeConfirmItem($itemTitle,$itemName,$postData,$errMsg);
    }
}
?>

    <div class="row mt-1 mb-3 text-center">
        <div class="col-4">
        </div>
        <div class="col-2">
            <form method="POST" class="d-grid" name="event_detail_input" action="<?php $isUpdate?print('./edit.php'):print('./input.php'); ?>">
<?php foreach($postData as $key => $value){ ?>
                <input type="hidden" name="<?php print($key); ?>" value="<?php print($value); ?>">
<?php } ?>
                <input class="" type="hidden" name="status" id="status" value="confirm">
                <button class="btn btn-outline-secondary" type="submit">戻る</button>
            </form>
        </div>
<?php if(empty($errMsg)){ ?>
        <div class="col-2">
            <form method="POST" class="d-grid" name="event_detail_input" action="./complete.php">
<?php foreach($postData as $key => $value){ ?>
                <input type="hidden" name="<?php print($key); ?>" value="<?php print($value); ?>">
<?php } ?>
                <input class="" type="hidden" name="status" id="status" value="confirm">
                <button class="btn btn-success" type="submit">完了</button>
            </form>
        </div>
    </div>
<?php } ?>


</main>
<?php include(ROOT.'/template/footer.php'); ?>
