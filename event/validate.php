<?php
function eventPostDataNormalize($postData){
    if(empty($postData['id'])){  // 更新時はIDの入力あり
        unset($postData['id']);
    }
    if(empty($postData['is_update']) || !in_array($postData['is_update'],['true','TRUE','True'])){  // 更新のフラグ
        unset($postData['id']);
    }
    if(empty($postData['status']) || !in_array($postData['status'],['target-input','detail-input','confirm','finish'])){
        $postData['status'] = 'target-input';
    }
    if(empty($postData['organizer_name'])){
        $postData['organizer_name'] = '';
    }
    if(empty($postData['event_name'])){
        $postData['event_name'] = '';
    }
    if(empty($postData['event_type'])){
        $postData['event_type'] = '';
    }
    if(empty($postData['event_date'])){
        $postData['event_date'] = '';
    }
    if(empty($postData['start_time'])){
        $postData['start_time'] = '';
    }
    if(empty($postData['end_time'])){
        $postData['end_time'] = '';
    }
    if(empty($postData['pref_name'])){
        $postData['pref_name'] = '';
        $postData['area_block'] = '';
    }else{
        if(in_array($postData['pref_name'],array_keys(PREF_NAME_TO_PREF_CODE))){
            $postData['area_block'] = PREF_CODE_TO_BLOCK_NAME[PREF_NAME_TO_PREF_CODE[$postData['pref_name']]];
            // POSTの値を強制的に修正
            $_POST['area_block'] = PREF_CODE_TO_BLOCK_NAME[PREF_NAME_TO_PREF_CODE[$postData['pref_name']]];
        }else{
            $postData['area_block'] = '';
            // POSTの値を強制的に修正
            $_POST['area_block'] = '';
        }
    }
    if(empty($postData['area_city'])){
        $postData['area_city'] = '';
    }
    if(empty($postData['place_name'])){
        $postData['place_name'] = '';
    }
    if(empty($postData['instructor_type'])){
        $postData['instructor_type'] = '';
    }
    if(empty($postData['instructor_1_name'])){
        $postData['instructor_1_name'] = '';
    }
    if(empty($postData['instructor_2_name'])){
        $postData['instructor_2_name'] = '';
    }
    if(empty($postData['instructor_name'])){
        $postData['instructor_name'] = '';
    }
    // 数値入力項目はissetで存在確認
    if(!isset($postData['no_read_user_num'])){
        $postData['no_read_user_num'] = '';
    }
    // 数値入力項目はissetで存在確認
    if(!isset($postData['read_user_num'])){
        $postData['read_user_num'] = '';
    }
    if(empty($postData['event_report'])){
        $postData['event_report'] = '';
    }
    if(empty($postData['boss_comment'])){
        $postData['boss_comment'] = '';
    }
    if(empty($postData['remark'])){
        $postData['remark'] = '';
    }
    if(empty($postData['viewing_movie'])){
        $postData['viewing_movie'] = '';
    }
    if(empty($postData['questionnaire'])){
        $postData['questionnaire'] = '';
    }
    return $postData;
}

function eventValidate($postData,$errMsg){
    // バリデーション
    if(empty($postData['is_update']) || !in_array($postData['is_update'],['true','TRUE','True'])){  // 更新のフラグ
        unset($postData['id']);
    }
    // 必須チェック
    if(empty($postData['event_name'])){
        $errMsg['event_name'] = 'イベント名を入力してください。';
        $postData['event_name'] = '';
    }
    if(empty($postData['event_type'])){
        $errMsg['event_type'] = 'イベント種別を入力してください。';
        $postData['event_type'] = '';
    }
    if(empty($postData['event_date'])){
        $errMsg['event_date'] = '開催日を入力してください。';
        $postData['event_date'] = '';
    }
    if(empty($postData['start_time'])){
        $errMsg['start_time'] = '開催時刻を入力してください。';
        $postData['start_time'] = '';
    }
    if(empty($postData['end_time'])){
        $errMsg['end_time'] = '終了時刻を入力してください。';
        $postData['end_time'] = '';
    }
    if(empty($postData['pref_name'])){
        $errMsg['pref_name'] = 'エリア（県）を入力してください。';
        $postData['pref_name'] = '';
    }
    if(empty($postData['area_block'])){
        $errMsg['area_block'] = 'ブロックが特定できませんでした。エリア（県）を入力してください。';
        $postData['area_block'] = '';
    }
    if(empty($postData['area_city'])){
        $errMsg['area_city'] = 'エリア（市町村）を入力してください。';
        $postData['area_city'] = '';
    }
    if(empty($postData['place_name'])){
        $errMsg['place_name'] = '開催場所を入力してください。';
        $postData['place_name'] = '';
    }
    if(empty($postData['instructor_type'])){
        $errMsg['instructor_type'] = '講師種別を入力してください。';
        $postData['instructor_type'] = '';
    }

    // 正の実数チェック
    if(isset($postData['id']) && !(isPosiInt($postData['id']))){    // 更新時のIDチェック
        $errMsg['id'] = 'イベント更新のIDが不正です。新規登録します。';
        $postData['id'] = '';
        exit();
        unset($postData['id']);
    }
    if(!(isPosiInt($postData['no_read_user_num']))){
        $errMsg['no_read_user_num'] = '未読者数は0以上で入力してください。';
        $postData['no_read_user_num'] = 0;
    }
    if(!(isPosiInt($postData['read_user_num']))){
        $errMsg['read_user_num'] = '愛読者数は0以上で入力してください。';
        $postData['read_user_num'] = 0;
    }

    // トグルボタン整合チェック
    $toggleButtonValue = ['FALSE','TRUE'];
    if(!in_array($postData['viewing_movie'],$toggleButtonValue)){
        $errMsg['viewing_movie'] = '動画視聴実施は'.$toggleButtonValue[1].'/'.$toggleButtonValue[0].'で入力してください。';
        $postData['viewing_movie'] = '';
    }
    if(!in_array($postData['questionnaire'],$toggleButtonValue)){
        $errMsg['questionnaire'] = 'アンケートは'.$toggleButtonValue[1].'/'.$toggleButtonValue[0].'で入力してください。';
        $postData['questionnaire'] = '';
    }

    // プルダウンチェック
    $salesList = [''=>'']+getSalesList();
    if(!in_array($postData['event_type'],array_values(EVENT_TYPE))){
        $errMsg['event_type'] = 'イベント種別は選択肢から選んでください。';
        $postData['event_type'] = '';
    }
    if(!in_array($postData['pref_name'],array_values(PREF_CODE_TO_PREF_NAME))){
        $errMsg['pref_name'] = 'エリア（県）は選択肢から選んでください。';
        $postData['pref_name'] = '';
    }
    if(!in_array($postData['instructor_type'],array_values(INSTRUCTOR_TYPE))){
        $errMsg['instructor_type'] = '講師種別は選択肢から選んでください。';
        $postData['instructor_type'] = '';
    }
    if(!in_array($postData['instructor_1_name'],array_keys($salesList))){
        $errMsg['instructor_1_name'] = '社員講師1は選択肢から選んでください。';
        $postData['instructor1_name'] = '';
    }
    if(!in_array($postData['instructor_2_name'],array_keys($salesList))){
        $errMsg['instructor_2_name'] = '社員講師2は選択肢から選んでください。';
        $postData['instructor2_name'] = '';
    }

    // 時刻チェック
    if(isTimeHHMM($postData['start_time'])){
        // OK
    }else if(isTimeHHMMSS($postData['start_time'])){
        $postData['start_time'] = substr($postData['start_time'],0,5);
    }else{
        $errMsg['start_time'] = '開催時刻はHH:MM形式で入力してください。';
        $postData['start_time'] = '';
    }
    if(isTimeHHMM($postData['end_time'])){
        // OK
    }else if(isTimeHHMMSS($postData['end_time'])){
        $postData['end_time'] = substr($postData['end_time'],0,5);
    }else{
        $errMsg['end_time'] = '終了時刻はHH:MM形式で入力してください。';
        $postData['end_time'] = '';
    }

    return ['postData'=>$postData,'errMsg'=>$errMsg];
}

?>