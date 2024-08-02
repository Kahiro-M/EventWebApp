<?php 
require_once('../config/env.php'); 
require_once(ROOT.'/define.php'); 
require_once(ROOT.'/event/define.php'); 
require_once(ROOT.'/common/db.php'); 
require_once(ROOT.'/common/common.php'); 
require_once(ROOT.'/common/member.php'); 
$htmlTitle="イベント情報入力";
$adminOnly = false;
$cssList=[
    '../css/navbar.css',
    '../css/event/input.css',
];
$jsList=['../js/navbar.js'];
require_once(ROOT.'/template/login_collect.php');
require_once(ROOT.'/event/validate.php');
require_once(ROOT.'/event/helper.php');

// 会員番号取得
$memberCode = '';
$targetInfo = [];
$errMsg = [];
if(!empty($_POST['member_code'])){
    $memberCode = getMemberNumber($_POST['member_code']);
    $postData['status'] = h($_POST['status']);
}
if(!empty($_POST['member_code']) && empty($_POST['organizer_name']) && empty($memberCode)){
    $errMsg['member_code'] = '会員番号は6桁で入力してください。';
}else{
    $targetInfo = getMemberInfo($memberCode);
    if(empty($targetInfo) && empty($_POST['organizer_name']) && !empty($memberCode)){
        // 二重登録防止用のセッション廃棄
        unset($_SESSION['uuid']);
        $errMsg['member_code'] = '指定された会員番号が見つかりません。';
        $errMsg['organizer_name'] = '会員番号を未入力の場合、主催者名を入力してください。';
        $memberName = '';
        $memberNameKana = '';
        $memberNameTitle = '';
        $memberType = '';
        $memberClass = '';
    }else if(empty($targetInfo) && empty($_POST['organizer_name']) && empty($memberCode)){
        // 二重登録防止用のセッション廃棄
        unset($_SESSION['uuid']);
        $memberName = '';
        $memberNameKana = '';
        $memberNameTitle = '';
        $memberType = '';
        $memberClass = '';
    }else if(empty($targetInfo) && !empty($_POST['organizer_name'])){
        $memberName = '';
        $memberNameKana = '';
        $memberNameTitle = '';
        $memberType = '';
        $memberClass = '';
    }else{
        $memberName = $targetInfo[$memberCode]['member_name'];
        $memberNameKana = $targetInfo[$memberCode]['name_kana'];
        $memberNameTitle = $targetInfo[$memberCode]['name_title'];
        $memberType = $targetInfo[$memberCode]['type'];
        $memberClass = $targetInfo[$memberCode]['class'];
    }
}

// POSTが無い場合の入力項目初期設定と最低限のデータ整形
if(!empty($_POST)){
    $postDataDirty = [];
    $postData = [];
    foreach($_POST as $key=>$value){
        $postDataDirty[$key] = h($value);
    }
    $postData = eventPostDataNormalize($postDataDirty);

    // バリデーション
    if(in_array($postData['status'],['detail-input'])){
        $validateRet = eventValidate($postData,$errMsg);
        $postData = $validateRet['postData'];
        $errMsg = $validateRet['errMsg'];
    }

    if(empty($errMsg) && in_array($postData['status'],['detail-input'])){
        header("Location: ./confirm.php", true, 307);
        exit();
    }
    // 二重登録防止用のセッション
    $uuid = generateUUIDv4();
    // セッションにUUIDを登録
    $_SESSION['uuid'] = $uuid;
}

$toggleButtonValue = ['FALSE','TRUE'];

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

<?php if(empty($targetInfo) && empty($postData['organizer_name'])){ // 会員番号と主催者名が入力されていない場合 ?>
    <form method="POST" name="event_target_input" action="./input.php">
        <div class="row mb-1">
            <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end">
                <label class="form-label" for="member_code">会員番号</label>
            </div>
            <div class="input col-sm-3 themed-grid-col">
                <input class="form-control" type="text" name="member_code" id="member_code" placeholder="000000" maxlength="6">
            </div>
        </div>
        <div class="row mb-1">
            <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end">
                もしくは、
            </div>
        </div>
        <div class="row mb-1">
            <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end">
                <label class="form-label" for="organizer_name">主催者名</label>
            </div>
            <div class="input col-sm-3 themed-grid-col">
                <input class="form-control" type="text" name="organizer_name" id="organizer_name" value="">
            </div>
        </div>

<?php makeSubmitButton('詳細へ','status','target-input','outline-success'); ?>
        <div class="row text-right">
            <div class="label col-4 mt-2 themed-grid-col text-end">
            </div>
            <div class="input col-6 themed-grid-col">
            </div>
        </div>
    </form>
<?php } ?>


<?php if(!empty($targetInfo) || !empty($postData['organizer_name'])){ // 会員番号か主催者名が入力された場合 ?>
    <form method="POST" name="event_detail_input" action="./input.php">
<?php   if(!empty($targetInfo)){ ?>
        <div class="row mb-1">
            <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">
                会員番号
            </div>
            <div class="input ms-3 ms-sm-0 col-sm-6 themed-grid-col">
                <?php print($memberCode); ?>
                <input type="hidden" id="member_code" name="member_code" value="<?php print($memberCode); ?>">
            </div>
        </div>
        <div class="row mb-1">
            <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">
                会員名
            </div>
            <div class="input ms-3 ms-sm-0 col-sm-6 themed-grid-col">
                <?php print($memberName); ?>
                <input type="hidden" id="target_name" name="member_name" value="<?php print($memberName); ?>">
                </div>
        </div>
        <div class="row mb-1">
            <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">
                種別
            </div>
            <div class="input ms-3 ms-sm-0 col-sm-6 themed-grid-col">
                <?php print(TYPE_CODE_TO_TYPE_NAME[$memberType]); ?>
                <input type="hidden" id="member_type" name="member_type" value="<?php print(TYPE_CODE_TO_TYPE_NAME[$memberType]); ?>">
            </div>
        </div>
        <div class="row mb-1">
            <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">
                クラス
            </div>
            <div class="input ms-3 ms-sm-0 col-sm-6 themed-grid-col">
                <?php print(CLASS_NAME[$memberClass]); ?>
                <input type="hidden" id="member_class" name="member_class" value="<?php print(CLASS_NAME[$memberClass]); ?>">
                </div>
        </div>
<?php   }else{ ?>
        <div class="row mb-1">
            <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">
                主催者名
            </div>
            <div class="input ms-3 ms-sm-0 col-sm-6 themed-grid-col">
                <?php print($postData['organizer_name']); ?>
                <input type="hidden" id="organizer_name" name="organizer_name" value="<?php print($postData['organizer_name']); ?>">
                </div>
        </div>
<?php   } ?>

<?php makeInputText('イベント名','event_name',$postData,$errMsg,TRUE); ?>
<?php makeInputSelectEventType('イベント種別','event_type',$postData,$errMsg,TRUE); ?>
<?php makeInputDate('開催日','event_date',$postData,$errMsg,TRUE); ?>
<?php makeInputTime('開催時刻','start_time',$postData,$errMsg,TRUE); ?>
<?php makeInputTime('終了時刻','end_time',$postData,$errMsg,TRUE); ?>
<?php makeInputSelectPrefName('エリア（県）','pref_name',$postData,$errMsg,TRUE); ?>
<?php makeInputText('エリア（市町村）','area_city',$postData,$errMsg,TRUE); ?>
<?php makeInputText('開催場所','place_name',$postData,$errMsg,TRUE); ?>
<?php makeInputSelectInstructorType('講師種別','instructor_type',$postData,$errMsg,TRUE); ?>
<?php makeInputSelectSalesInstructorName('社員講師1','instructor_1_name',$postData,$errMsg,FALSE); ?>
<?php makeInputSelectSalesInstructorName('社員講師2','instructor_2_name',$postData,$errMsg,FALSE); ?>
<?php makeInputText('講師名','instructor_name',$postData,$errMsg,FALSE); ?>
<?php makeInputNumber('未読者数','no_read_user_num',$postData,$errMsg,FALSE); ?>
<?php makeInputNumber('愛読者数','read_user_num',$postData,$errMsg,FALSE); ?>
<?php makeInputTextarea('イベント報告','event_report',$postData,$errMsg,FALSE); ?>
<?php makeInputTextarea('上司コメント','boss_comment',$postData,$errMsg,FALSE); ?>
<?php makeInputTextarea('備考','remark',$postData,$errMsg,FALSE); ?>
<?php makeInputToggle('動画視聴実施','viewing_movie',$postData,$errMsg,$toggleButtonValue); ?>
<?php makeInputToggle('アンケート','questionnaire',$postData,$errMsg,$toggleButtonValue); ?>

        <input type="hidden" id="member_code" name="member_code" required value="<?php print($memberCode); ?>">
        <input class="" type="hidden" name="uuid" id="uuid" value="<?php print($_SESSION['uuid']); ?>">

<?php makeSubmitButton('入力','status','detail-input','outline-success'); ?>
    </form>
<?php } ?>
</main>

<?php include(ROOT.'/template/footer.php'); ?>
