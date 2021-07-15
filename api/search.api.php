<?php
/**
 *      Title:      Search API
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    Process search requests and return results
 *      
 */
require_once(__DIR__.'/../lib/db.class.php');

if( isset($_REQUEST['q'] ) or true){
    
    $q_term = $_REQUEST['q'];

    $search_q = filter_var($q_term, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    $sql = "SELECT books.book, text.chapter, text.verse, text.text  FROM kjv.text LEFT JOIN books ON text.book=books.id WHERE MATCH (`text`) AGAINST ('".$search_q."' IN NATURAL LANGUAGE MODE) LIMIT 25;";

    $db = new db();
    $db_rows = $db->query($sql)->fetchAll();

    $response =
    '{
        "results": [';

    foreach($db_rows as $row){
        $response .='{
            "book" : "'.$row["book"].'",
            "chap" :  "'.$row["chapter"].'",
            "verse":  "'.$row["verse"].'",
            "text" :  "'.$row["text"].'"
        },';
    }

    $response = substr($response, 0 , -1);  // Remove trailing comma
    $response .=']}';

}else{  $response = ''; }

echo $response;
?>