<?php
 /**
  * @author Joe Cohen <joe@dingocode.com>
  */

class Bible
{
    public function __construct(){}
    
    public static function getBooksAsLinks($testament = 'all'){
        $links = '';
        $db = new DB();
        $q = "SELECT books.book FROM kjv.books ORDER BY id ASC LIMIT 66";
        $row = $db->query($q)->fetchAll();

        $start = 0;
        $end = 65;
        if ($testament != 'all') {
            $start  = ($testament == 'new') ? 39 : 0;
            $end    = ($testament == 'old') ? 38 : 65;
        }  
        
        for ($i = $start; $i <= $end; $i++) {
            $book = $row[$i]['book'];

            $links .= '<li><a class="" href="'.lcfirst($book).'/">' . $book . '</a></li>';
        }

        return $links;
    } 
}
