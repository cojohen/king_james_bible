<?php
/**
 *      Title:      page.class.php
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:
 *      
 *      Parse an HTML file containing one chapter of KJV and output
 *      a CSV file with all HTML tags removed.
 *      
 */

class Page {
    
    public string $pageStr;
    public int $book;
    public int $chap; 

    public function __construct($path = ''){

        $this->pageStr = file_get_contents($path);

        $file = explode('/', $path);
        $file = end($file);
        $book = NULL;
        $chap = NULL;
        preg_match('/B(\d{2})/', $file, $book);
        preg_match('/C(\d{3}).htm/', $file, $chap);
        
        $this->book = (int)$book[1];
        $this->chap = (int)$chap[1];
    }

    public function toCSV(){
        $CSV = $this->pageStr;

        //Remove new lines
        $CSV = preg_replace('{\v}' , '', $CSV);
        
        // Remove head down to first verse
        $CSV = preg_replace('{<HTML>(.+?)</H4></TD><TD><P>}s' , '', $CSV);
        
        // Remove elements between verses
        $CSV = preg_replace('{</P></TD>(.+?)<TD><P>}s' , "\n", $CSV);
        $CSV = preg_replace('{</TD>(.+?)<TD><P>}s' , "\n", $CSV);

        // Remove <a>'s
        $CSV = preg_replace('{<A(.+?)>}s' , '', $CSV);
        $CSV = preg_replace('{<a(.+?)>}s' , '', $CSV);


        // Remove </a>'s
        $CSV = preg_replace('{</A>}' , '', $CSV);
        
        // Remove all <br> ... \n
        //$CSV = preg_replace('{<BR>(.+?)\n}' , "\n", $CSV);
        //$CSV = preg_replace('{<BR>(.+\s)}' , "\n", $CSV);
        
        // Remove footer beginning with </P></TD>
        $CSV = preg_replace('{</P></TD>(.+?)</HTML>}s' , '', $CSV);
        $CSV = preg_replace('{</TD></TR>(.+?)</HTML>}s' , '', $CSV);
        $CSV = preg_replace('{<BR>(.+?)</HTML>}s' , '', $CSV);
        $CSV = preg_replace('{</TD>(.+?)</HTML>}s' , '', $CSV);
        
        
        $CSV = preg_replace('{<BR>(.+\s)}' , "\n", $CSV);
        $CSV = preg_replace('{<BR>(.+?)\n}s' , '', $CSV);
        $CSV = preg_replace('{<br>(.+?)\n}s' , '', $CSV);
        
        //echo $CSV;
        
        $CSV = explode("\n", $CSV);

        $CSVReturn = '';

        foreach($CSV as $verse){

            // format "(book)01,(chapter)02,Bible verse ...
            $CSVReturn .= $this->book ."##". $this->chap ."##". trim($verse) ."\n";

        }

        return $CSVReturn;
    }
}

?>