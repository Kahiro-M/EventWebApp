<?php
function getMemberNumber($memberCode){
    if(preg_match('/^\d{0,6}$/',$memberCode)){
        $memberCode = sprintf('%06d',(int)($memberCode));
    }else{
        $memberCode = '';
    }
    return $memberCode;
}

function getMemberInfo($memberCode){
    $db = connect_db();

    $sql = "SELECT * FROM `".MEMBER_TABLE_STR."` WHERE `".MEMBER_CODE_STR."` LIKE '$memberCode' ORDER BY `".MEMBER_TABLE_STR."`.`".MEMBER_CODE_STR."` ASC";
    $ret = $db->query($sql);
    $retInfo = array();
    while ($row = db_fetch($ret)) {
        $retInfo[$row[MEMBER_CODE_STR]] = [
            'member_name' => $row[MEMBER_NAME_STR],
            'name_kana'   => $row[MEMBER_NAME_KANA_STR],
            'name_title'  => $row[MEMBER_TITLE_STR],
            'type'        => $row[MEMBER_TYPE_STR],
            'class'       => $row[MEMBER_CLASS_STR],
        ];
    }
    return  $retInfo;
}



?>
