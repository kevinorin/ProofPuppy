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
<?php include 'puppylogonav.php'; ?>
  <!--end navbar-->
 
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
    
    
    
    
    $user_email_id= $_POST['user_email_id'];
    
    $instructions = $_POST['instructions'];

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

    <!--   <div id="room_fileds">
           <div>
            <div class="content"> -->

                <form class="UpForm" method="post">
                    <?php
                    $htmlouput= '<table border="0">
                        <tr>
                        <td><label for="user_email">Your email:</label></td><br/>
                        </tr>
                        <tr><td>
                         <input style="width:300px;" type="email" required="" name="user_email_id" value="'.$user_email_id.'" /></td><br/>
                        </tr><tr></tr><br>
                        <tr>
                        <td><label for="instructions">Any Message or Special Instructions ?</label></td><br/>
                        </tr>
                        <tr><td>
                         <textarea style="width:500px; min-height: 200px;" name="instructions">'. $instructions .'</textarea></td><br/>
                        </tr><tr></tr>
                        <tr>
                            <td>URL</td>
                            <td>Words</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url0" value="'.$urlarray[0].'" /></td>
                            <td>'.$words[0].'</td>
                            <td>'.$error[0].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url1" value="'.$urlarray[1].'" /></td>
                            <td>'.$words[1].'</td>
                            <td>'.$error[1].'</td>
                        </tr>
                        <tr>
                            <td> <input type="text" style="width:250px;" name="url2" value="'.$urlarray[2].'" /></td>
                            <td>'.$words[2].'</td>
                            <td>'.$error[2].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url3" value="'.$urlarray[3].'" /></td>
                            <td>'.$words[3].'</td>
                            <td>'.$error[3].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url4" value="'.$urlarray[4].'" /></td>
                            <td>'.$words[4].'</td>
                            <td>'.$error[4].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url5" value="'.$urlarray[5].'" /></td>
                            <td>'.$words[5].'</td>
                            <td>'.$error[5].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url6" value="'.$urlarray[6].'" /></td>
                            <td>'.$words[6].'</td>
                            <td>'.$error[6].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url7" value="'.$urlarray[7].'" /></td>
                            <td>'.$words[7].'</td>
                            <td>'.$error[7].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url8" value="'.$urlarray[8].'" /></td>
                            <td>'.$words[8].'</td>
                            <td>'.$error[8].'</td>
                        </tr>
                        <tr>
                            <td><input type="text" style="width:250px;" name="url9" value="'.$urlarray[9].'" /></td>
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
  <!--end form-->
  
  
  
  
  
  
  
  
  



  
    </div>
  </div>
</div>


  <!-- FOOTER-->
  <!--===============================================================-->
  
<?php include 'puppyfooter.php'; ?>
<!--end footer-->
  </body>
</html>