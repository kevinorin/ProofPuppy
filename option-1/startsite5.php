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
        <a class="scroll-smooth" href="http://www.proofpuppy.com">
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
      <h1 class="header-title">Enter the site you would like proofed</h1> 
        <h2 class="subtitle">Please enter the website URL:</h2>
        <hr>
        </div>
      </div>  
  
  
  
  <!--start form-->
  <?php

require_once 'getCount.php';
require_once 'emailer.php';

$WordCount = new WordCount();
?>

<html>
<head>

</head>
<body>
<form method="post">
    <input type="text" name="url" placeholder="Enter URL Here!" />
    <input type="submit" name="Submit" value="Count"/>
</form>
</body>
</html>

<?php

if(isset($_POST['Submit']) && $_POST['Submit'] == 'Count')
{
    $url = $_POST['url'];

    $urlerror = $WordCount->checkurl($url);

    if((isset($urlerror)))
    {
        echo $urlerror;
        exit;
    }
//set url session for background if needed.

    $_SESSION['url'] = rtrim($_POST['url'], '/');

    $url = $_SESSION['url'];
//start the counting.
    $parsedUrl = parse_url($url);

    $result = $WordCount->getPage($url);

    if($result['errno'] !=0)
    {
        die('<h3>There was a problem with the entered URL, probably a malformation, be sure the URL starts with http://
        , or http://www. AND ends with .com, .net, .org, or .gov</h3><br> '.$result['errno'].' '.$result['errmsg']);
    }

    if($result['http_code'] != 200)
    {
        die('page doesnt not exist, no permission, or no service!');
    }

    $page = $result['content'];

    $stripedcontent = $WordCount->htmlStrip($page);

    $links = $WordCount->getLinks($stripedcontent);
    $stripedcontent = $WordCount->striplinks($stripedcontent);
//"http://www.proofpuppy.com/backgroundjobs.php"
    if(!empty($links))
    {
        $linkcount = count($links);
        if($linkcount >= 60)
        {
            $_SESSION['links'] = $links;
            echo '
            <script type="text/javascript">
                window.location = "backgroundjobs.php";
            </script>
            ';
            exit;
        }
        else
        {
            $words = $WordCount->getCount($stripedcontent);

            $output[] ='<html>
                    <head>
                    </head>
                    <body>
                        <table>
                            <tr><td>Page</td><td>&nbsp;</td><td>Words</td>
                    ';


            $output[] = '<tr><td><a href="'.$url.'">'.$url.'</a></td><td>&nbsp;</td><td>'.$words.'</td></tr>';
            $linkWordCount = $WordCount->followLinks($links);

            $totalwordcount = 0;

            foreach($linkWordCount[1] as $key => $value)
            {
                if(stristr($key, 'http://') !== false && strpos($key, $parsedUrl['host']) !== false)
                {
                    $output[] = '<tr><td><a href="'.$key.'">'.$key.'</a></td><td>&nbsp;</td><td>'.$value.'</td></tr>';
                    $totalwordcount += $value;
                }
                else
                {
                    $output[] = '<tr><td><a href="'.$url.$key.'">'.$url.$key.'</a></td><td>&nbsp;</td><td>'.$value.'</td></tr>';
                    $totalwordcount += $value;
                }
            }

            $lastarrayitem = count($linkWordCount[1]) + 2;
            $output[$lastarrayitem] = '<tr>
                                            <td>&nbsp;</td><td>&nbsp;</td>
                                       </tr>
                                       <tr>
                                            <td><b>Total Words</b></td><td>&nbsp;</td><td>'.$totalwordcount.'</td>
                                       </tr>
                                       </table>
                                       </body>
                                       </html>';
        }
    }
    foreach($output as $value)
    {
        echo $value;
    }
    $email = new email();
    $email->fullsite($output);
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