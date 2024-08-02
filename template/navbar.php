<?php $loginInfo = getLoginInfo(); ?>                    
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="/menu.php">SAMPLE WEB APP</a>
        <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/menu.php'){print('active');} ?>" <?php if($_SERVER['REQUEST_URI'] == '/menu.php'){print('aria-current="page"');} ?> href="/menu.php">TOP</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/event/input.php'){print('active"');} ?>" <?php if($_SERVER['REQUEST_URI'] == '/event/menu.php'){print('aria-current="page"');} ?> href="/event/input.php">イベント情報入力</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/event/show_list.php'){print('active"');} ?>" <?php if($_SERVER['REQUEST_URI'] == '/event/show_list.php'){print('aria-current="page"');} ?> href="/event/show_list.php">イベント情報照会</a>
                </li>
                <?php if($_SESSION['admin']>0){ ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/log/show_list.php'){print('active"');} ?>" <?php if($_SERVER['REQUEST_URI'] == '/log/show_list.php'){print('aria-current="page"');} ?> href="/log/show_list.php">ログ参照</a>
                </li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav mb-lg-0">
                <li class="nav-item text-info fw-bold">
<?php
    print($loginInfo['user_name']);
if($loginInfo['admin'] == ROLE_ADMIN){
    print('('.$loginInfo['role'].')');
}
    print('　');
?>
                </li>
            </ul>
            <form class="d-flex" role="logout" method="POST" name="logout" action="../logout.php">
                <button class="btn btn-outline-success" type="submit">ログアウト</button>
            </form>
        </div>
    </div>
</nav>
