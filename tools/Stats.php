<?php 
/**
 *      Title:      Stats.php
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:
 *      
 *      Calculate statistics for the dataset
 *      
 */

require_once(__DIR__.'/../lib/db.class.php');

$db = new db();

// Fetch books
$books = $db->query('SELECT id, book from `books` ORDER BY `id`')->fetchAll();

// Build table
$write = '<table border="0">';

foreach($books as $bk){
    // book.id and book.name
    $write .= "<tr><td>".$bk['id']."</td><td>".$bk['book']."</td>";

    // chapter count
    $chap_q = 'SELECT COUNT(DISTINCT verses.chapter) AS `count` FROM `verses` WHERE verses.book='.$bk['id'];
    $chap_cnt = $db->query($chap_q)->fetchArray();
    
    $write .= "<td>".$chap_cnt['count']."</td>";

    // verse count
    $vers_q = 'SELECT COUNT(verses.id) AS `count` FROM `verses` WHERE verses.book='.$bk['id'];
    $vers_cnt = $db->query($vers_q)->fetchArray();
    
    $write .="<td>".$vers_cnt['count']."</td></tr>\n";
    
}

$write .= '</table>';

?>

<style>
    body {
        background: #333;
        color: #e8e8e8;
        text-align: center;
        font-size: 10pt;
        font-family: 'Courier New', Courier, monospace;
    }

    table {
        margin: 4em auto;
        font-size: 10pt;
    }
</style>

<?php 
    echo $write; 

?>
