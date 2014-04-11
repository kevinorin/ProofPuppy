<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ProofPuppy is a live-website proofing service. Get rid of typos, spelling errors and basic grammer mistakes.">
    <meta name="author" content="ProteaDigital">
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
      <h1 class="header-title">Enter the site you would like proofed</h1> 
        <h2 class="subtitle">Please enter the website URL:</h2>
        <p>Note: this can take up to 30 seconds to complete</p>
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
    <input type="text" name="url" placeholder="http://www.example.com" />
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

    //$_SESSION['url'] = rtrim($_POST['url'], '/');
    $_SESSION['url'] = $_POST['url'];

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


  <!-- FOOTER-->
  <!--===============================================================-->
  
<?php include 'puppyfooter.php'; ?>
<!--end footer-->
  </body>
</html>