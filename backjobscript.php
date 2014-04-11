<?php
/**
 * Created by PhpStorm.
 * User: micheal
 * Date: 3/17/14
 * Time: 10:29 PM
 */

//initiate lib
    require_once 'getCount.php';
    $wordcount = new WordCount();

ini_set('session.use_cookies', '0');
session_id($argv[1]);
session_start();

$links = $_SESSION['links'];
$url = $_SESSION['url'];


//start the proccess
    if(!empty($links))
    {
        $result = $wordcount->getPage($url);

        if($result['errno'] !=0)
        {
            echo 'error some were sorry: '. $result['errno'].' '.$result['errmsg'];
            die();
        }

        if($result['http_code'] != 200)
        {
            echo 'page doesnt not exist or no permission, or no service!';
            die();
        }

        $page = $result['content'];

        $stripedcontent = $wordcount->htmlStrip($page);

        $words = $wordcount->getCount($stripedcontent);

//begining of html output PU ANY CSS YOU WANT HERE!!!!!
        $output = array();

        $output[] ='<html>
                    <head>
                    </head>
                    <bod y>
                        <table>
                            <tr><td>Page</td><td>Words</td>
                    ';

        $output[] = '<tr><td><a href="'.$url.'</a>'.$url.'</td><td>'.$words.'</td></tr>';

        $linkWordCount = $wordcount->followLinks($links);

        foreach($linkWordCount[1] as $key => $value)
        {
            $output[] = '<tr><td><a href="'.$url.$key.'">'.$url.$key.'</a></td><td>'.$value.'</td></tr>';
        }

        $lastarrayitem = count($linkWordCount[1]) + 1;
        $output[$lastarrayitem] = '</table></body></html>';
//end of html Output

//write output to file
        $date = date('m-d-y');
        $time = time();
        $filename = $date.'-'.$time.'.html';

        $data = implode(' ', $output);
        if(file_put_contents($filename, $data) == false)
        {
            echo 'There was an error writing the file!';
        }
    }
?>