
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Start</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 60px;
      }

      /* Custom container */
      .container {
        margin: 0 auto;
        max-width: 1000px;
      }
      .container > hr {
        margin: 60px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 80px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 100px;
        line-height: 1;
      }
      .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }


      /* Customize the navbar links to be fill the entire space of the .navbar */
      .navbar .navbar-inner {
        padding: 0;
      }
      .navbar .nav {
        margin: 0;
        display: table;
        width: 100%;
      }
      .navbar .nav li {
        display: table-cell;
        width: 1%;
        float: none;
      }
      .navbar .nav li a {
        font-weight: bold;
        text-align: center;
        border-left: 1px solid rgba(255,255,255,.75);
        border-right: 1px solid rgba(0,0,0,.1);
      }
      .navbar .nav li:first-child a {
        border-left: 0;
        border-radius: 3px 0 0 3px;
      }
      .navbar .nav li:last-child a {
        border-right: 0;
        border-radius: 0 3px 3px 0;
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>
<!--logo and menu-->
     <?php include 'heading.php'; ?>

<!--logo and menu end-->

      <hr>
        
  

<?php
# if upload files present
if (isset($_FILES['file']))
{
	# subdirectory where the uploaded files will be locaetd
	$uploadDir = "word_count_files";


	if ($_FILES["file"]["error"] > 0)
	{
		$errors['upload']=$_FILES["file"]["error"];
	}
	else
	{
		$filename=$_FILES["file"]["name"];//get the name of the file
		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		# if extension is allowed, move file to upload dir
		if(in_array($extension, array('doc','docx','ppt','pptx')) )
		{
	
			$filename = "$uploadDir/".date('Ymdhis').$_FILES["file"]["name"];

			move_uploaded_file(
				$_FILES["file"]["tmp_name"],
				$filename
			);

			# incdlude WordCount class
			require_once('include/WordCount.php');

			# count words
			$wordCounter = new WordCounter($filename);
			$wordCount = $wordCounter->countWords();

		}
		else
		{
			$errors['extension']="The extension <strong>$extension</strong> is not allowed. Please use only doc, docx, ppt, pptx files.";
		}
	}		
}

# if there is an errors show the form agian, or no upload file present
if (isset($errors) || !isset($_FILES['file']))
{
	?>
      <div class="row-fluid">
        <div class="span4">
          <h2>Start by uploading </h2>
          <p>must be .doc .docx   .ppt or .pptx</p>
          <p>&nbsp;</p>
        </div>

	<?php
	# output errors
	if (isset($errors))
	{
		foreach ($errors as $error)
		{
			?>	
			<div class="span4 alert alert-danger">
				<?php echo $error; ?>
			</div>
			<?php
		}
	}
	?>

		<!--file upload form-->

		<form method="post"
		enctype="multipart/form-data">
		<label for="file">Filename:</label>
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Submit">
		</form>
      </div>
		<!--file upload form end -->
	<?php
}
# no error, show the word count
else
{
	?>
	<div class="row-fluid">
		<div class="span4 alert alert-success">
			Your file has been succesfully uploaded. 
		</div>
	</div>
	<?php
	if ($wordCount)
	{
		
		$pricePerWord=0.03;
		$costEst = $wordCount * $pricePerWord;
		?>
	<div class="row-fluid">
		<div class="span4">
			<p>This file has <?php echo $wordCount; ?> words</p>
			<p>The estimated cost is <?php echo $costEst ?> dollars</p>
		</div>
	</div>		
	<div class="row-fluid">
		<div class="span4">

		<?php
	
		require_once('include/checkout.php');
		print_checkout($costEst);
	}
	else
	{
		?>
	<div class="row-fluid">
		<div class="span4 alert alert-danger">
			We were unable to count the number of words in your file automatically, and give you a price estimate
		</div>
	</div>

	<?php
	}

	# send mail
	require_once('include/PHPMailer/PHPMailerAutoload.php');

	$adminMail = "proofpuppy@proteadigital.com" ;
	#$adminMail = "martin.taleski@gmail.com" ;

	$mailBody = "A new file has been uploaded\n\n";
	if($wordCount) {
		# word count present
		$mailBody .= "The file has $wordCount words\n";
		$mailBody .= "The cost estimate is $costEst dollars\n\n";
	}
	else {
		# unable to count words
		$mailBody .= "We were unable to count the words automatically and give a cost estimate\n";
	}
	$mailBody .= "You can download the file here: http://proofpuppy/$filename\n\n";
	$mailBody .= "--\n";
	$mailBody .= "this is an automated message sent from proofpuppy.com\n";
	$mailBody .= "please do not reply";

	$mail = new PHPMailer();
	$mail->setFrom('no-reply@proofpuppy.com', 'Proofpuppy');
	$mail->addAddress($adminMail);
	$mail->Subject = 'New file uploaded';
	$mail->Body=$mailBody;
	$mail->ContentType = 'text/plain'; 
    $mail->IsHTML(false);
	$mail->addAttachment($filename);
	
	if (!$mail->send()) {
    	echo "Mailer Error: " . $mail->ErrorInfo;
	}
	
	?>
	</div>
	<?php

}
?>


      <!--test aread

<form role="form">
 
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="exampleInputFile">
    <p class="help-block">Please upload your file for proofing here.</p>
  </div>
  
  <button type="submit" class="btn btn-default">Word Count</button>
</form>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<button type="button" class="btn btn-primary btn-success btn-lg">Checkout</button>
  <a href="/startdocs.php">
-->

<!--test areas-->




    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>



      <div class="footer">
 <?php include 'footercode.php'; ?>
      </div>
      
  </body>
</html>
