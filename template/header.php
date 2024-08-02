<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サンプルWEBアプリ
<?php
if(isset($htmlTitle)){
    print(' | '.htmlspecialchars($htmlTitle,ENT_QUOTES,'UTF-8'));
}
?>
    </title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.min.css">
<?php 
if(isset($cssList)){
    foreach($cssList as $cssPath){
?>
    <link rel="stylesheet" type="text/css" href="<?php if(!empty($cssPath)){print(htmlspecialchars($cssPath,ENT_QUOTES,'UTF-8'));} ?>">
<?php
    }
 }
?>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery.timepicker.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body class="d-flex align-items-center bg-body-tertiary">
