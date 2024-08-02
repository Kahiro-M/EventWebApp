<pre>
<?php
$str = 'ハッシュ化したいパスワード';
print($str.' : '.password_hash($str,PASSWORD_DEFAULT));
?>
</pre>
