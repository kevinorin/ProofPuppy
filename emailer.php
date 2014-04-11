<?php
/**
 * Created by PhpStorm.
 * User: micheal
 * Date: 3/25/14
 * Time: 4:01 PM
 */
class email
{
    function SinglePage($output)
    {
        $date = date('m-d-y');
        $time = time();
        $output = $output.'</body></html>';

        file_put_contents('single-page/singlesearch-'.$date.'-'.$time.'.html', $output, LOCK_EX);

        $reciever = 'proofpuppy@proteadigital.com';
        $msg = '<html><head></head><body> The link to the following page is <a href="http://www.proofpuppy.com/single-page/singlesearch-'.$date.'-'.$time.'.html">singlesearch-'.$date.'-'.$time.'.html</a><br><br><br>'.$output;
        $subject = 'New Site Search!';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($reciever, $subject, $msg, $headers);
    }

    function ServerPage($output)
    {
        $date = date('m-d-y');
        $time = time();
        $output = $output.'</body></html>';

        file_put_contents('site-search/sitesearch-'.$date.'-'.$time.'.html', $output, LOCK_EX);

        $reciever = 'proofpuppy@proteadigital.com';
        $msg = '<html><head></head><body> The link to the following page is <a href="http://www.proofpuppy.com/site-search/sitesearch-'.$date.'-'.$time.'.html">sitesearch-'.$date.'-'.$time.'.html</a><br><br><br>'.$output;
        $subject = 'New Site Search!';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($reciever, $subject, $msg, $headers);
    }

    function fullsite($output)
    {
        $date = date('m-d-y');
        $time = time();

        file_put_contents('site-search/sitesearch-'.$date.'-'.$time.'.html', $output, LOCK_EX);

        $reciever = 'proofpuppy@proteadigital.com';
        $msg = 'The link to the following page is <a href="http://www.proofpuppy.com/site-search/sitesearch-'.$date.'-'.$time.'.html">singlesearch-'.$date.'-'.$time.'.html</a><br><br><br>'.$output;
        $subject = 'New Site Search!';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($reciever, $subject, $msg, $headers);
    }
}