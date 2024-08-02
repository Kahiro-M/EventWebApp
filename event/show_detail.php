<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
require_once(ROOT.'/common/member.php'); 
$htmlTitle="イベント情報照会 | 詳細";
$adminOnly = false;
$cssList=[
    '../css/navbar.css',
];
$jsList=['../js/navbar.js'];
require_once(ROOT.'/template/login_collect.php');
require_once(ROOT.'/event/validate.php');
require_once(ROOT.'/event/helper.php');

// DB接続
$db = connect_db();

$param = $_GET;
// ID指定が不正な場合は強制ログアウト
if(!isPosiInt(h($param['id']))){
    header("Location: http://". $_SERVER["HTTP_HOST"] ."/logout.php");
    exit();
}

// SQL文発行
$id = $db->quote($param['id']);
$sql ="SELECT * FROM `".EVENT_TABLE_STR."` WHERE `id`=$id";
$ret = $db->query($sql);
writeLog($htmlTitle."
    SQL:
    ".$sql);
$searchRet = array();
while ($row = db_fetch($ret)) {
    $searchRet[$row['id']] = $row;
}

if(count($searchRet)>1){
    $errMsg['search_result'] = '検索結果が複数あります。管理者にお問い合わせ下さい。';
    $searchRet = NULL;
}else if(count($searchRet)<1){
    header("Location: ./show_list.php");
}else{
    $searchRet = array_values($searchRet)[0];
}

// 検索結果の値の整形
$postData = [];
foreach($searchRet as $key=>$value){
    $postData[$key] = h($value);
}
// 編集画面遷移に使用するPOST
$postData['is_update'] = 'true';

$postData = eventPostDataNormalize($postData);

// バリデーションチェック
$errMsg = [];
$validateRet = eventValidate($postData,$errMsg);
$postData = $validateRet['postData'];
$errMsg = $validateRet['errMsg'];

$staffCodeNameList = getUserIdNameList();

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

// 詳細表示時はIDも表示
makeConfirmItem('ID','id',$postData,$errMsg);
// 入力項目の表示
foreach(ITEM_LIST as $itemName => $itemTitle){
    makeConfirmItem($itemTitle,$itemName,$postData,$errMsg);
}
// 作成者名などがあれば表示する
if(isset($postData['created_user_name'])){
    makeConfirmItem('作成者名','created_user_name',$postData,$errMsg);
}else{
    $tmp['created_user_name'] = $staffCodeNameList[$postData['created_user']];
    makeConfirmItem('作成者名','created_user_name',$tmp,$errMsg);
}
if(isset($postData['created'])){
    makeConfirmItem('作成日時','created',$postData,$errMsg);
}
if(isset($postData['updated_user_name'])){
    makeConfirmItem('更新者名','updated_user_name',$postData,$errMsg);
}else{
    $tmp['updated_user_name'] = $staffCodeNameList[$postData['updated_user']];
    makeConfirmItem('更新者名','updated_user_name',$tmp,$errMsg);
}
if(isset($postData['updated'])){
    makeConfirmItem('更新日時','updated',$postData,$errMsg);
}
?>

    <div class="row mt-1 mb-3 text-center">
        <div class="col-4">
        </div>
        <div class="col-2 text-end">
            <a href="./show_list.php" class="btn btn-secondary" role="button">一覧へ戻る</a>
        </div>
        <div class="col-2">
            <form method="POST" class="d-grid" name="event_detail_edit" action="./edit.php">
<?php foreach($postData as $key => $value){ ?>
                <input type="hidden" name="<?php print($key); ?>" value="<?php print(hd($value)); ?>">
<?php } ?>
                <input class="" type="hidden" name="status" id="status" value="target-input">
                <button class="btn btn-success" type="submit">編集画面へ</button>
            </form>
        </div>
    </div>


</main>
<?php include(ROOT.'/template/footer.php'); ?>
