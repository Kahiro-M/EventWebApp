<?php

define('EVENT_TYPE',[
    1 => 'セミナー',
    2 => 'ウェビナー',
    3 => '展示会',
    4 => '研修会',
]);

define('INSTRUCTOR_TYPE',[
    1 => '社員',
    2 => '外部',
    3 => 'ユーザ',
]);

// 入力項目=>Salesforceの項目名
define('FORM_TO_SALESFORCE',[
    'member_code'                => 'Salesforceの項目名',
    'organizer_name'             => 'Salesforceの項目名',
    'event_name'                 => 'Salesforceの項目名',
    'event_type'                 => 'Salesforceの項目名',
    'event_date'                 => 'Salesforceの項目名',
    'start_time'                 => 'Salesforceの項目名',
    'end_time'                   => 'Salesforceの項目名',
    'pref_name'                  => 'Salesforceの項目名',
    'area_block'                 => 'Salesforceの項目名',
    'area_city'                  => 'Salesforceの項目名',
    'place_name'                 => 'Salesforceの項目名',
    'instructor_type'            => 'Salesforceの項目名',
    'instructor_1_name'          => 'Salesforceの項目名',
    'instructor_2_name'          => 'Salesforceの項目名',
    'instructor_name'            => 'Salesforceの項目名',
    'no_read_user_num'           => 'Salesforceの項目名',
    'read_user_num'              => 'Salesforceの項目名',
    'event_report'               => 'Salesforceの項目名',
    'boss_comment'               => 'Salesforceの項目名',
    'remark'                     => 'Salesforceの項目名',
    'viewing_movie'              => 'Salesforceの項目名',
    'questionnaire'              => 'Salesforceの項目名',
]);

define('ITEM_LIST',[
    'member_code'                => '会員番号',
    'member_name'                => '会員名',
    'member_type'                => '種別',
    'member_class'               => 'クラス',
    'organizer_name'             => '主催者名',
    'event_name'                 => 'イベント名',
    'event_type'                 => 'イベント種別',
    'event_date'                 => '開催日',
    'start_time'                 => '開催時刻',
    'end_time'                   => '終了時刻',
    'pref_name'                  => 'エリア（県）',
    'area_block'                 => 'ブロック',
    'area_city'                  => 'エリア（市町村）',
    'place_name'                 => '開催場所',
    'instructor_type'            => '講師種別',
    'instructor_1_name'          => '社員講師1',
    'instructor_2_name'          => '社員講師2',
    'instructor_name'            => '講師名',
    'no_read_user_num'           => '未読者数',
    'read_user_num'              => '愛読者数',
    'event_report'               => 'イベント報告',
    'boss_comment'               => '上司コメント',
    'remark'                     => '備考',
    'viewing_movie'              => '動画視聴実施',
    'questionnaire'              => 'アンケート実施',
]);

define('DOWNLOAD_FORMAT',[
    'salesforce' => 'dl4sf',
    'general' => 'dl4gl',
]);

?>