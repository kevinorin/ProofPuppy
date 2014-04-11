<?php
# subdirectory where the uploaded files will be locaetd
$upload_dir = "word_count_files";

if ($_FILES["file"]["error"] > 0)
{
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
	die();
}

$filename = "$upload_dir/".date('Ymdhis').$_FILES["file"]["name"];

move_uploaded_file(
	$_FILES["file"]["tmp_name"],
	$filename
);

?>

<div>
Your file has been succesfully uploaded. You can download it <a href="<?php echo $filename; ?>">here</a>
</div>
