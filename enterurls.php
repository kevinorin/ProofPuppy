
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SiteorURL</title>
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
  
  
  
  <!--adding button start
<script>
var room = 1;
function add_fields() {
    room++;
    var objTo = document.getElementById('room_fileds')
    var divtest = document.createElement("div");
    divtest.innerHTML = '<div class="label">URL Page ' + room +':</div><div class="content"><span>www.<input type="text" style="width:180px;" name="www.[]" value="" /> </div>';
    
    objTo.appendChild(divtest)
}
     
	 </script>   
adding button end-->
  
  
  
  </head>

  <body>
<!--logo and menu-->
     <?php include 'heading.php'; ?>

<!--logo and menu end-->
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

            </div>
           </div>
        </div>






      <hr>



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
