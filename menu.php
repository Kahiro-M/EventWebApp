<?php 
require_once('./config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/common/member.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
$htmlTitle="TOP";
$adminOnly = false;
$cssList=[
    '../css/navbar.css',
];
$jsList=['../js/navbar.js'];
require_once(ROOT.'/template/login_collect.php');
include(ROOT.'/template/header.php');
include(ROOT.'/template/navbar.php');
?>
<main class="container">
<?php if(!empty($errMsg)){ // エラーメッセージ ?>
    <div class="d-flex align-items-center p-3 my-3 text-white bg-red rounded shadow-sm">
        <div class="lh-1">
            <h1 class="h6 mb-0 text-white lh-1"><?php print($errMsg); ?></h1>
        </div>
    </div>
<?php } ?>
    <div class="d-flex align-items-center p-3 my-3 text-white bg-teal rounded shadow-sm text-center">
        <div class="container lh-1">
            <h1 class="h6 mb-0 text-white lh-1">サンプルWEBアプリ</h1>
            <small>Since 2024</small>
        </div>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h6 class="border-bottom pb-2 mb-0">各位へ</h6>
        <div class="d-flex text-body-secondary pt-3">
            <p class="pb-3 mb-0 small lh-sm border-bottom">
                サンプル情報入力は上部のバーの「<a href="/event/input.php">イベント情報入力</a>」からお願いします。
            </p>
        </div>
    </div>
</main>

<?php include(ROOT.'/template/footer.php'); ?>