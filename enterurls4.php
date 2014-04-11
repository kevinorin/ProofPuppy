<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Flanzo is a blazing fast responsive HTML5 CSS3 Bootstrap 3 template featuring great hover effects, responsive lightbox, working contact form, blog page and more">
    <meta name="author" content="Mamoot Studio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started</title>
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
          <img class="navbar-brand" src="http://www.proofpuppy.com/images/proof-pup-logo-beta-flat-small-v1.png" alt="logo">
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
  
 
  <!-- SERVICES-->
  <!--===============================================================-->
<div id="services">
  <div class="bg-services">
    <div class="container">
      <div class="row-services-header row">
        <div class="col-sm-12 wow fadeInUp">
          <h1 class="header-title">GET STARTED</h1>
          <h2 class="subtitle">Get results in less than 24 hours.</h2>
          <hr>
        </div>
      </div>
      
  <?php


if(isset($majorError))
{
    echo '<h3>'.$majorError.'</h3>';
}
require_once 'getCount.php';
require_once 'emailer.php';

if(isset($_POST['count']))
{

    $wordcount = new WordCount();
    $error = array();
    $words = array();

    $i=-1;
    $urlarray = array();

    while($i <= 8)
    {
        $i++;
        $urlarray[] = $_POST['url'.$i];

    }

    $urlarray = array_values($urlarray);
    $urlarray = array_unique($urlarray);

    foreach($urlarray as $key => $values)
    {
        if($values === "" || $values === "/")
        {
            unset($urlarray[$key]);
        }
    }

    foreach($urlarray as $value)
    {
        $result = $wordcount->getPage($value);
        if($result['errno'] !=0)
        {
            $error[] = 'There was a problem with the entered URL, probably a malformation, be sure the URL starts with http://
        , or http://www. AND ends with .com, .net, .org, or .gov, '.$result['errno'].' '.$result['errmsg'];
            continue;
        }

        if($result['http_code'] != 200)
        {
            $error[] = 'page doesnt not exist, no permission, or no service!';
            continue;
        }

        if($result['content'] === false)
        {
            $majorError = 'There was a real big problem with .'.$value. ' please remove the page and try again. this error is out of our control.';
            continue;
        }



        $page = $result['content'];
        $stripedcontent = $wordcount->htmlStrip($page);
        $stripedcontent = $wordcount->striplinks($stripedcontent);
        $words[] = $wordcount->getCount($stripedcontent);
        $total = 0;
        foreach($words as $valued)
        {
            $total += $valued;
        }
    }
}



?>


<!-- <input type="button" class="btn btn-default" id="more_fields" onclick="add_fields();" value="Add more urls of pages to be proofed" /> -->

       <div id="room_fileds">
           <div>
            <div class="content">

                <form method="post">
                    <?php

                    $htmlouput= '<table border="0">
                        <tr>
                            <td>URL</td>
                            <td>Words</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url0" value="'.$urlarray[0].'" /></td>
                            <td>'.$words[0].'</td>
                            <td>'.$error[0].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url1" value="'.$urlarray[1].'" /></td>
                            <td>'.$words[1].'</td>
                            <td>'.$error[1].'</td>
                        </tr>
                        <tr>
                            <td> <input type="text" style="width:180px;" name="url2" value="'.$urlarray[2].'" /></td>
                            <td>'.$words[2].'</td>
                            <td>'.$error[2].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url3" value="'.$urlarray[3].'" /></td>
                            <td>'.$words[3].'</td>
                            <td>'.$error[3].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url4" value="'.$urlarray[4].'" /></td>
                            <td>'.$words[4].'</td>
                            <td>'.$error[4].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url5" value="'.$urlarray[5].'" /></td>
                            <td>'.$words[5].'</td>
                            <td>'.$error[5].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url6" value="'.$urlarray[6].'" /></td>
                            <td>'.$words[6].'</td>
                            <td>'.$error[6].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url7" value="'.$urlarray[7].'" /></td>
                            <td>'.$words[7].'</td>
                            <td>'.$error[7].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url8" value="'.$urlarray[8].'" /></td>
                            <td>'.$words[8].'</td>
                            <td>'.$error[8].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:180px;" name="url9" value="'.$urlarray[9].'" /></td>
                            <td>'.$words[9].'</td>
                            <td>'.$error[9].'</td>
                        </tr>
                        <tr>
                            <td>
                            <b>Total</b>
                            </td>
                            <td>
                            '.$total.'
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr><td colspan="3"> <input type="submit" name="count" value="Count" /> </td></tr>
                    </table>
                </form>';
                    echo $htmlouput;

					if  ($total>0)
					{
						require_once('include/checkout.php');

						$pricePerWord=0.03;
						$costEst = $total * $pricePerWord;

						print_checkout($costEst);
					}
                    $email = new email();
                    $email->SinglePage($htmlouput);
                ?>



<br></br>
<br></br>
<br></br>
<br></br>


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