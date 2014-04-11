
<!DOCTYPE html>
<html lang="en">
<head>
    
  </head>

  <body>
<!--logo and menu-->

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









<!--test areas-->





    </div> <!-- /container -->


  </body>
</html>
