<?php
    // /* Aerialbot1.0 *\ Limitation: Our crawler will only fetch anchor tags from a website. Advance crawling will be in furthur updates.
    // This is a open source code of /*Aerialbot1.0 *\ and can be only used for educational purpose.
    // I will not be responsible for crawling to private websites and illegally collecting data for unfair means.  
    $start = "http://inlite.ml/?i=1";   // Enter your link here.
    $crawled = array();                 // Crawled array is to store already crawled links 
    $crawling = array();                // Crawling array is to store links which is being stored but not crawled.
    $value = array();                   // Value array is to store fetched website data
    global $crawled;                   
    global $crawling;
    global $value;
    function details($url){             //details function to fetch data of a website.
        global $crawled;
        global $crawling;
        global $value;
        $doc = new DOMDocument();
        @$doc->loadHTML(@file_get_contents($url));
        $title = $doc->getElementsByTagName("title");
        $title = $title[0]->nodeValue;
        $description = "";
	    $keywords = "";
        $meta = "";
        $metas = $doc->getElementsByTagName("meta");
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if (strtolower($meta->getAttribute("name")) == "description")
                $description = $meta->getAttribute("content");
            if (strtolower($meta->getAttribute("name")) == "keywords")
                $keywords = $meta->getAttribute("content");
        }
        $linklist = $doc->getElementsByTagName("a");        // Only fetch anchor tags from a webiste source code
        foreach($linklist as $link){                        
            //code to check the link
            $l =  $link->getAttribute("href");
            if (substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//") {
                $l = parse_url($url)["scheme"]."://".parse_url($url)["host"].$l;
            } else if (substr($l, 0, 2) == "//") {
                $l = parse_url($url)["scheme"].":".$l;
            } else if (substr($l, 0, 2) == "./") {
                $l = parse_url($url)["scheme"]."://".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($l, 1);
            } else if (substr($l, 0, 1) == "#") {
                $l = parse_url($url)["scheme"]."://".parse_url($url)["host"].parse_url($url)["path"].$l;
            } else if (substr($l, 0, 3) == "../") {
                $l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
            } else if (substr($l, 0, 11) == "javascript:") {
                continue;
            } else if (substr($l, 0, 5) != "https" && substr($l, 0, 4) != "http") {
                $l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
            }
            // end of checking;
        }
            $title2 = str_replace("\n", "", $title);
            $desc2 = str_replace("\n", "", $description);
            $keywords2 = str_replace("\n", "", $keywords);
            $url2 = str_replace("\n", "", $url);
            return array($title2,$desc2,$keywords2,$url2);
    }
    function follow_links($url){        // follow_links function to follow $url;
        global $crawled;
        global $crawling;
        global $value;
        if(!in_array($url,$crawled)){
            if(!in_array($url,$crawling)){
                $crawled[] = $url;
            }
        }
        $doc = new DOMDocument();
        @$doc->loadHTML(@file_get_contents($url));
        $value = details($url);
        $linklist = $doc->getElementsByTagName("a");
        foreach($linklist as $link){
            $l =  $link->getAttribute("href");
            if (!in_array($l, $crawled)) {
                if(!in_array($l,$crawling)){
                    $crawling[] = $l;
                }
            }
        }
        $value = details($url);
        echo "Title:".$value[0]."\n";           // print title
        echo "Description:".$value[1]."\n";     // print Description
        echo "keywords".$value[2]."\n";         // print keywords if any
        echo "URL:".$value[3]."\n";             // print URL of crawled 
        echo "<hr/> \n";                        // hr tag to remove cluttering
    }
    follow_links($start);                       // It calls follow_links once
    while(1){                                   // while loop to call follow_links function multiple times.
        $m = array_shift($crawling);            
    if($m == ""){
        break;                                  // break only at node which never comes.
    }
        follow_links($m);
    }
?>
