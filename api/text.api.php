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
//require_once(__DIR__.'/../lib/db.class.php');
require_once(__DIR__.'/../lib/collection.class.php');

$searchTerm = '"jesus wept" and the a "so loved the world"';

$collection = new Collection();
$collection->searchVerses($searchTerm);
$JSON = $collection->toJSON(TRUE);

echo $JSON;
?>