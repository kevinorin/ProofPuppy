<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Flanzo is a blazing fast responsive HTML5 CSS3 Bootstrap 3 template featuring great hover effects, responsive lightbox, working contact form, blog page and more">
    <meta name="author" content="Mamoot Studio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProofPuppy - Proofread live sites or documents.</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700,300,800' rel='stylesheet' type='text/css'>
    <!-- PLUGINS-CSS-->
    <!--===============================================================-->   
    <link rel="stylesheet" href="css-theme/font-awesome.min.css" type="text/css" >
    <link rel="stylesheet" href="css-theme/nivo-lightbox.min.css" type="text/css" >
    <!-- THEME-CSS-->
    <!--===============================================================--> 
    <link rel="stylesheet" href="css-theme/theme.css" type="text/css">
    <link rel="stylesheet" href="css-theme/animate.min.css" type="text/css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
<body data-spy="scroll" data-target=".scroll-bootstrap" data-offset="50">
  <!-- NAVBAR-->
  <!--===============================================================-->
  <nav class="navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <a class="scroll-smooth" href="#home">
          <img class="navbar-brand" src="http://www.proofpuppy.com/images/proof-pup-logo-beta-rectangle.png" alt="logo">
        </a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="scroll-bootstrap collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a class="scroll-smooth" href="#home">Home</a></li>
          <li><a class="scroll-smooth" href="#services">How</a></li>
          <li><a class="scroll-smooth" href="#work">Clients</a></li>
          <li><a class="scroll-smooth" href="#news-blog">Benefits</a></li>
          <li><a class="scroll-smooth" href="#contact">Contact</a></li> 
          <li><a  href="http://www.proofpuppy.com/start3.html">START</a></li>
        </ul>
      </div>
    </div>
  </nav>
 
<!-- POSTS -->
<!--===============================================================-->
<div id="news-blog">
  <div class="bg-blog">
    <div class="container">
      <div class="row-blog-header row">
        <div class="col-sm-12">
      <h1 class="header-title">Enter pages you would like proofed.</h1> 
        <h2 class="subtitle">Please enter the page URLs you would like proofed:</h2>
        <hr>
        </div>
      </div>  
  
  
  
  <!--start form-->
  
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

<br></br>
<br></br>
<br></br>
<br></br>
  <!--end form-->
  
  
  
  
  
  
  
  
  



  
    </div>
  </div>
</div>

<!-- SUBSCRIBE-->
<!--===============================================================-->
<div class="bg-subscribe">
  <div class="container-subscribe container">
    <div class="row-subscribe row">
      <div class="col-subscribe col-sm-8 col-sm-offset-2 wow fadeInRight">
          <input type="email" class="form-control form-flat subscribe-mail hidden-xs" placeholder="Add your email address to get our newsletter">
          <!-- Removes placeholder at XS -->
          <input type="email" class="form-control form-flat subscribe-mail visible-xs">
          <a href="#" class="btn btn-flat">SUBSCRIBE</a>
      </div>
    </div>
  </div>
</div>
  <!-- FOOTER-->
  <!--===============================================================-->
  <div class="bg-footer">
    <div class="container">
      <div class="row row-footer">
        <div class="col-md-12">
          <div class="wrapper-social">
            <a href="#"><i class="fa fa-facebook facebook-footer wow bounce"></i></a>
            <a href="#"><i class="fa fa-twitter twitter-footer wow bounce"  data-wow-delay="0.2s"></i></a>
       <!--     <a href="#"><i class="fa fa-dribbble dribbble-footer wow bounce"  data-wow-delay="0.3s"></i></a> -->
            <a href="#"><i class="fa fa-tumblr youtube-footer wow bounce"  data-wow-delay="0.3s"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- FOOTER-BOTTOM-->
  <!--===============================================================-->
  <div class="bg-footer-bottom">
    <div class="container">
      <div class="row-footer-bottom row">
        <div class="col-sm-12 hidden-xs">
          <p class="pull-left">&copy;2014 All Rights Reserved by Protea Digital.</p>
          <div class="links-footer pull-right">
            <a class="scroll-smooth" href="#services">Services</a>
            <a class="scroll-smooth" href="#work">Work</a>
            <a class="scroll-smooth" href="#news-blog">Posts</a>
            <a class="scroll-smooth" href="#home">Top</a>
          </div>
        </div>

        <!-- VISIBLE XS REMOVES CLASS "PULL-RIGHT" AND CENTERS-->
        <div class="hidden-sm visible-xs">
          <p>&copy;2013 All Rights Reserved by Mamoot Studio.</p>
          <div class="links-footer">
            <a class="scroll-smooth" href="#services">Services</a>
            <a class="scroll-smooth" href="#work">Work</a>
            <a class="scroll-smooth" href="#news-blog">Posts</a>
            <a class="scroll-smooth" href="#home">Top</a>
          </div>
        </div>

      </div>
    </div>
  </div>
    <!-- JAVASCRIPT-->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js-theme/wow.min.js"></script>
    <script>
      wow = new WOW(
        {
          animateClass: 'animated',
          mobile: false
        }
      );
      wow.init();
    </script>
    <script src="js-theme/jquery.bxslider.min.js"></script>
    <script src="js-theme/nivo-lightbox.min.js"></script>
    <script src="js-theme/jquery.mixitup.min.js"></script>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiUHrtP7COzKY2azegkJZzps3J7pQ4Qs4&sensor=false">
    </script> 
    <script src="js-theme/call.js"></script> 
  </body>
</html>