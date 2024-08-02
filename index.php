<?php
require_once('./config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 

$htmlTitle="INDEX";
$cssList=["../css/index.css"];
$jsList=[''];

require_once('./template/login_check.php');
include('./template/header.php');
?>


<main class="form-signin w-100 m-auto">
    <form class="text-center" action="index.php" method="post">
        <img class="mb-4" src="../img/login_logo.png" alt="" width="200"  loading="lazy">
        <h1 class="h3 mb-3 fw-normal">SAMPLE WEB APP　ログイン</h1>
        <h1 class="h4 mb-3 fw-bold text-danger">
<?php
    if(isset($errMsg)){
        print($errMsg);
    }
?>
        </h1>
        
        <div class="form-floating">
            <input type="text" class="form-control" id="login_id" name="login_id" required placeholder="abc1234">
            <label for="login_id">ログインID</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="パスワード">
            <label for="password">パスワード</label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">ログイン</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; 2024</p>
    </form>
</main>

<?php include('./template/footer.php'); ?>