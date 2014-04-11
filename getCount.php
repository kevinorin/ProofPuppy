<?php



class WordCount
{
    protected $linkList;
    protected $parsedUrl;
    public $linkwordcount;
    public $error;

    function checkurl($url)
    {

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
           $this->error = 'Not a valid URL';
        }



        return $this->error;
    }

    function getPage($url)
    {

        $this->parsedUrl = parse_url($url);

        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    function htmlStrip($content)
    {
        preg_match_all('/<body.*>(.*?)<\/body>/s', $content, $matches);
        if(isset($matches[0][0]))
        {
            $content = $matches[0][0];
        }
        else
        {
            $content = $matches[0];
        }

        //$content = html_entity_decode($content);
//strip out JS tages and content and all HTML tags except <a>6
        $js_striped = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $content);

        $stripedContent = strip_tags($js_striped,'<a>');

        return $stripedContent;
    }
    function striplinks($content)
    {
        $stripedlinks = strip_tags($content);

        return $stripedlinks;
    }

    function followLinks($links)
    {
        $starttime = time();
        //loop through link array and run functions
        foreach($links as $key => $value)
        {

            if($value == $_SESSION['url'])
            {
                unset($links[$key]);
                continue;
            }
            //|| stristr($value, 'http://www.') !== false
            if(stristr($value, 'http://') !== false && strpos($value, $this->parsedUrl['host']) === false)
            {
                unset($links[$key]);
                continue;
            }
            else
            {
                if(strpos($value, $this->parsedUrl['host']) !== false)
                {
                    $childUrl = $value;
                }
                else
                {
                    $childUrl = $this->parsedUrl['scheme'].'://'.$this->parsedUrl['host'].'/'.$value;
                }

            }

            $childPage = self::getPage($childUrl);


            if($childPage['errno'] == 0 && $childPage['http_code'] == 200)
            {
                $childPage = $childPage['content'];
                $stripedContent = self::htmlStrip($childPage);

                $childLinks = self::getLinks($stripedContent);

                $stripedContent = self::striplinks($stripedContent);

                //combine arrays and filter out unique links.
                $this->linkList = array_unique(array_merge($links, $childLinks), SORT_STRING);
                array_values($this->linkList);

                $words = self::getCount($stripedContent);

                //echo $value.' has '.$words.' words.<br>';
                $this->linkwordcount[$value] = $words;
            }
        }


        return array($this->linkList, $this->linkwordcount);
    }

    function getCount($countString)
    {
        $words = str_word_count($countString);
        return $words;
    }

    function getLinks($input)
    {
        preg_match_all('~<a href="(.*?)"~i', $input, $links);



        $links = $links[1];

        //clean the link array

        foreach($links as $key => &$value)
        {
            if(strlen($value) <= 1)
            {
                unset($links[$key]);
            }
            if(strpos($value, 'http://'.$this->parsedUrl['host'] ) == false)
            {
                if(strpos($value, 'http://') == true)
                {
                    unset($links[$key]);
                }
            }
        }

        //reset array keys
        foreach($links as $key => $values)
        {
            if($values === "" || $values === "/")
            {
                unset($links[$key]);
            }
        }

        $links = array_values($links);
        $links = array_unique($links);
        if(in_array($this->parsedUrl['scheme'].'://'.$this->parsedUrl['host'], $links) == true)
        {
            unset($links[$this->parsedUrl['scheme'].'://'.$this->parsedUrl['host']]);
        }

        return $links;
    }

    function createhtml($email)
    {
        $output = file_get_contents($email.'.txt');

        $links = explode(',,', $output);

        return $links;
    }
}