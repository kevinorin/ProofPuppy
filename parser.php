<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>



<?php

$curl = curl_init('http://www.proofpuppy.com/index-old.html');

//make content be returned by curl_exec rather than being printed immediately                                 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl);

if ($result !== false) {
    if (preg_match('|<title>(.*)</title>|i', $result, $matches)) {
        echo "Title is '{$matches[1]}'";   
    } else {
        //did not find the title    
    }
} else {
    //request failed
    die (curl_error($curl)); 
}
?>
<p> other part</p>

<?php

// Fetch remote html
$contents =$curl;

// Get rid of style, script etc
$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
           '@<head>.*?</head>@siU',            // Lose the head section
           '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
           '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
);

$contents = preg_replace($search, '', $contents); 

$result = array_count_values(
              str_word_count(
                  strip_tags($contents), 1
                  )
              );
			  ?>
              
<body>
<?php

print_r($result);
?>
<?php

$url = 'http://www.proofpuppy.com/index-old.html';

function getHTML($url) {
    if($url == false || empty($url)) return false;
    $options = array(
    	CURLOPT_URL            => $url,     // URL of the page
    	CURLOPT_RETURNTRANSFER => true,     // return web page
    	CURLOPT_HEADER         => false,    // don't return headers
    	CURLOPT_FOLLOWLOCATION => true,     // follow redirects
    	CURLOPT_ENCODING       => "",       // handle all encodings
    	CURLOPT_USERAGENT      => "spider", // who am i
    	CURLOPT_AUTOREFERER    => true,     // set referer on redirect
    	CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
    	CURLOPT_TIMEOUT        => 120,      // timeout on response
    	CURLOPT_MAXREDIRS      => 3,       // stop after 3 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    //Ending all that cURL mess...


    //Removing linebreaks,multiple whitespace and tabs for easier Regexing
    $content = str_replace(array("\n", "\r", "\t", "\o", "\xOB"), '', $content);
    $content = preg_replace('/\s\s+/', ' ', $content);
    $this->profilehtml = $content;
    return $content;
}
echo "last here";
echo "$url";
?>










</body>

</html>
