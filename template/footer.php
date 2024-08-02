
</body>
<?php 
if(isset($jsList)){
    foreach($jsList as $jsPath){
?>
<script src="<?php if(!empty($jsPath)){print(htmlspecialchars($jsPath,ENT_QUOTES,'UTF-8'));} ?>"></script>
<?php
    }
 }
?>
<script src="../js/common.js"></script>
</html>

