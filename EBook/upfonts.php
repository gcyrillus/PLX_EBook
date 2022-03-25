 <?php
 if(file_exists('EBook.php') ){
$arr_file_ext = ['ttf', 'otf', 'woff', 'woff2'];

 foreach($_FILES as $filename) {
	 $ext=pathinfo($filename['name']);
	 if(!in_array($ext['extension'],$arr_file_ext)) {
		echo " bad type";
		return;
	 }
}
  
if (!file_exists('fonts')) {
    mkdir('fonts', 0777);
}
  
$filename = $_FILES['file']['name'];
  
move_uploaded_file($_FILES['file']['tmp_name'], 'fonts/'.$filename);
  
echo trim($filename);
die;
 }
 else { echo 'PLX_EBook tool';}
?>