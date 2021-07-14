<?php 
/**
 *      Title:      DBTest.php
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:
 *      
 *      Test DB Connection
 *      
 */

require_once(__DIR__.'/../lib/db.class.php');

$db = new db();

$rows = $db->query('SELECT chapter,verse FROM verses WHERE `book`=1 ORDER BY `id` ')->fetchAll();

//print_r($rows);

$current_chap = (int)$rows[0]['chapter'];
$current_verse = 1;

echo "<h2>Chp: $current_chap</h2>";

foreach($rows as $row){
    
    if( $current_chap < (int)$row['chapter'] ){
        $current_chap = (int)$row['chapter'];
        echo "<h2>Chp: $current_chap</h2>";
        $current_verse = 1;
    }

    echo "<b> $current_verse. </b>".$row['verse'];

    $current_verse++; 
}



 ?>