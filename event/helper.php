<?php

function makeErrMsgInputForm($errMsg,$inputName){
    if(!empty($errMsg[$inputName])){
        print('    <div class="row">'."\n");
        print('        <div class="col-sm-4"></div>'."\n");
        print('        <div class="col-sm-8">'."\n");
        print('            <div class="mb-2 err">'.h($errMsg[$inputName]).'</div>'."\n");
        print('        </div>'."\n");
        print('    </div>'."\n");
    }
}

function makeInputText($title,$inputName,$postData,$errMsg,$required=FALSE){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-5">'."\n");
    if($required){
        print('        <input class="form-control" type="text" id="'.$inputName.'" name="'.$inputName.'" required value="'.$postData[$inputName].'">'."\n");
    }else{
        print('        <input class="form-control" type="text" id="'.$inputName.'" name="'.$inputName.'" value="'.$postData[$inputName].'">'."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeInputTextarea($title,$inputName,$postData,$errMsg,$required=FALSE){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-5">'."\n");
    if($required){
        print('        <textarea class="form-control" id="'.$inputName.'" name="'.$inputName.'" required>'.$postData[$inputName].'</textarea>'."\n");
    }else{
        print('        <textarea class="form-control" id="'.$inputName.'" name="'.$inputName.'">'.$postData[$inputName].'</textarea>'."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeInputNumber($title,$inputName,$postData,$errMsg,$required=FALSE){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    if($required){
        print('        <input class="form-control" type="number" id="'.$inputName.'" name="'.$inputName.'" required value="'.$postData[$inputName].'">'."\n");
    }else{
        print('        <input class="form-control" type="number" id="'.$inputName.'" name="'.$inputName.'" value="'.$postData[$inputName].'">'."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeInputDate($title,$inputName,$postData,$errMsg,$required=FALSE){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    if($required){
        print('        <input class="form-control" type="date" id="'.$inputName.'" name="'.$inputName.'" required value="'.$postData[$inputName].'">'."\n");
    }else{
        print('        <input class="form-control" type="date" id="'.$inputName.'" name="'.$inputName.'" value="'.$postData[$inputName].'">'."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeInputTime($title,$inputName,$postData,$errMsg,$required=FALSE,$min='06:00:00',$max='22:00:00'){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    if($required){
        print('        <input class="form-control timepicker" type="text" id="'.$inputName.'" name="'.$inputName.'" required value="'.$postData[$inputName].'">'."\n");
    }else{
        print('        <input class="form-control timepicker" type="text" id="'.$inputName.'" name="'.$inputName.'" value="'.$postData[$inputName].'">'."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeInputSelectEventType($title,$inputName,$postData,$errMsg,$required=FALSE){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    makeSelectEventType($inputName,$postData,$required);
    print('    </div>'."\n");

    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeSelectEventType($inputName,$postData,$required=FALSE,$addOpotion=[]){
    if($required){
        print('<select class="form-control" id="'.$inputName.'" name="'.$inputName.'" required>'."\n");
    }else{
        print('<select class="form-control" id="'.$inputName.'" name="'.$inputName.'">'."\n");
    }
    // 追加選択肢
    if(!empty($addOpotion)){
        foreach($addOpotion as $addOpotionValue => $addOpotionText){
            if($postData[$inputName] == $addOpotionValue){
                print('    <option value="'.$addOpotionValue.'" selected>'.$addOpotionText.'</option>');
            }else{
                print('    <option value="'.$addOpotionValue.'">'.$addOpotionText.'</option>');
            }
        }
    }
    // イベント種別選択肢
    foreach(EVENT_TYPE as $eventTypeCode => $eventTypeName){
        if($postData[$inputName] == $eventTypeName){
            print('    <option value="'.$eventTypeName.'" selected>'.$eventTypeName.'</option>');
        }else{
            print('    <option value="'.$eventTypeName.'">'.$eventTypeName.'</option>');
        }
    }
    print('</select>'."\n");
}

function makeInputSelectPrefName($title,$inputName,$postData,$errMsg,$required=FALSE){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    makeSelectPrefName($inputName,$postData,$required,TRUE);
    print('    </div>'."\n");

    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeSelectPrefName($inputName,$postData,$required=FALSE,$addBlockName=FALSE,$addOpotion=[]){
    if($required){
        print('<select class="form-control" id="'.$inputName.'" name="'.$inputName.'" required>'."\n");
    }else{
        print('<select class="form-control" id="'.$inputName.'" name="'.$inputName.'">'."\n");
    }
    // 追加選択肢
    if(!empty($addOpotion)){
        foreach($addOpotion as $addOpotionValue => $addOpotionText){
            if($postData[$inputName] == $addOpotionValue){
                print('    <option value="'.$addOpotionValue.'" selected>'.$addOpotionText.'</option>');
            }else{
                print('    <option value="'.$addOpotionValue.'">'.$addOpotionText.'</option>');
            }
        }
    }
    // 都道府県種別選択肢
    foreach(BLOCK_TO_PREF_LIST as $listBlockCode => $listBlockPrefCode){
        if($addBlockName){
            print('    <option class="block" data-block-pref-code="'.implode(',',$listBlockPrefCode).'">---ブロック：'.BLOCK_CODE_TO_BLOCK_NAME[$listBlockCode].'---</option>'."\n");
        }
        foreach($listBlockPrefCode as $listPrefCode => $listPrefName){
            if($postData[$inputName] == PREF_CODE_TO_PREF_NAME[$listPrefName]){
                print('    <option class="block-'.$listBlockCode.'" value="'.PREF_CODE_TO_PREF_NAME[$listPrefName].'" selected>'.PREF_CODE_TO_PREF_NAME[$listPrefName].'</option>');
            }else{
                print('    <option class="block-'.$listBlockCode.'" value="'.PREF_CODE_TO_PREF_NAME[$listPrefName].'">'.PREF_CODE_TO_PREF_NAME[$listPrefName].'</option>');
            }
        }
    }
    print('</select>'."\n");
}

function makeInputSelectInstructorType($title,$inputName,$postData,$errMsg,$required=FALSE){
    $salesList = [''=>'']+getSalesList();
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    if($required){
        print('        <select class="form-control" id="'.$inputName.'" name="'.$inputName.'" required>'."\n");
    }else{
        print('        <select class="form-control" id="'.$inputName.'" name="'.$inputName.'">'."\n");
    }
    foreach(INSTRUCTOR_TYPE as $instructorTypeCode => $instructorTypeName){
        if($postData[$inputName] == $instructorTypeName){
            print('            <option value="'.$instructorTypeName.'" selected>'.$instructorTypeName.'</option>');
        }else{
            print('            <option value="'.$instructorTypeName.'">'.$instructorTypeName.'</option>');
        }
    }
    print('        </select>'."\n");
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}



function makeInputSelectSalesInstructorName($title,$inputName,$postData,$errMsg,$required=FALSE){
    $salesList = [''=>'']+getSalesList();
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 mt-2 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-sm-3">'."\n");
    if($required){
        print('        <select class="form-control" id="'.$inputName.'" name="'.$inputName.'" required>'."\n");
    }else{
        print('        <select class="form-control" id="'.$inputName.'" name="'.$inputName.'">'."\n");
    }
    foreach($salesList as $salesCode => $salesName){
        if($postData[$inputName] == $salesName){
            print('            <option value="'.$salesCode.'" selected>'.$salesName.'</option>');
        }else{
            print('            <option value="'.$salesCode.'">'.$salesName.'</option>');
        }
    }
    print('        </select>'."\n");
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeInputToggle($title,$inputName,$postData,$errMsg,$valueOffOn){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-6 col-sm-4 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input col-3 form-check form-switch">'."\n");
    print('        <input class="" type="hidden" name="'.$inputName.'" value="'.$valueOffOn[0].'">'."\n");
    if($postData[$inputName] == $valueOffOn[1]){
        print('        <input class="form-check-input" type="checkbox" role="switch" id="'.$inputName.'" name="'.$inputName.'"  value="'.$valueOffOn[1].'" checked>'."\n");
    }else{
        print('        <input class="form-check-input" type="checkbox" role="switch" id="'.$inputName.'" name="'.$inputName.'"  value="'.$valueOffOn[1].'">'."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeConfirmItem($title,$inputName,$postData,$errMsg){
    print('<div class="row mb-1">'."\n");
    print('    <div class="label col-sm-4 themed-grid-col text-sm-end fw-bold">'."\n");
    print('        <label class="form-label" for="'.$inputName.'">'.$title.'</label>'."\n");
    print('    </div>'."\n");
    print('    <div class="input ms-3 ms-sm-0 col-sm-8">'."\n");
    if(isUpdate($postData) && !isset($postData['uuid'])){
        print('        '.nl2br(hd($postData[$inputName])).''."\n");
    }else{
        print('        '.nl2br($postData[$inputName]).''."\n");
    }
    print('    </div>'."\n");
    if(!empty($errMsg[$inputName])){
        makeErrMsgInputForm($errMsg,$inputName);
    }
    print('</div>'."\n");
}

function makeSubmitButton($title,$inputName,$postData,$color){
    print('    <div class="row mt-3 mb-3">'."\n");
    print('        <div class="col-sm-12 text-center">'."\n");
    print('            <input class="" type="hidden" id="'.$inputName.'" name="'.$inputName.'"  value="'.$postData.'">'."\n");
    print('            <button class="btn btn-'.$color.'" type="submit">'.$title.'</button>'."\n");
    print('        </div>'."\n");
    print('    </div>'."\n");
}

// Salesforceのイベント入力で選択できる値
function getSalesList(){
    return [
        '試験 太郎' => '試験 太郎',
        '試験 次郎' => '試験 次郎',
        '試験 三郎' => '試験 三郎',
        '左衛門三郎 江川富士一二三四五左衛門助太郎' => '左衛門三郎 江川富士一二三四五左衛門助太郎',
    ];
}

?>
