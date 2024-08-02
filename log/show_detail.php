<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/log_db.php'); 
require_once(ROOT.'/common/common.php'); 
$htmlTitle="ログ参照 | 詳細";
$adminOnly = true;
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
$sql ="SELECT * FROM `".LOG_DB_TABLE."` WHERE `id`=$id";
$ret = $db->query($sql);
// writeLog($htmlTitle."
//     SQL:
//     ".$sql);
$searchRet = array();
while ($row = db_fetch($ret)) {
    $searchRet[$row['id']] = $row;
}

$errMsg = [];

if(count($searchRet)>1){
    $errMsg['search_result'] = '検索結果が複数あります。管理者にお問い合わせ下さい。';
    $searchRet = NULL;
}else if(count($searchRet)<1){
    header("Location: ./show_list.php");
}else{
    $searchRet = array_values($searchRet)[0];
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

<?php
// 入力項目の表示
foreach($searchRet as $itemTitle => $itemValue){
    if(!is_numeric($itemTitle)){
        print('<div class="row mb-1">'."\n");
        print('    <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">'."\n");
        print('        <label class="form-label" for="'.$itemTitle.'">'.$itemTitle.'</label>'."\n");
        print('    </div>'."\n");
        print('    <div class="input ms-3 ms-sm-0 col-sm-8">'."\n");
        print('        '.nl2br(hd($itemValue)).''."\n");
        print('    </div>'."\n");
        print('</div>'."\n");
    }
}
?>

    <div class="row mt-1 mb-3 text-center">
        <div class="col-12 text-center">
            <a href="./show_list.php" class="btn btn-secondary" role="button">一覧へ戻る</a>
        </div>
    </div>


</main>
<?php include(ROOT.'/template/footer.php'); ?>
