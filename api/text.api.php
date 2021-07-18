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
require_once(__DIR__.'/../lib/collection.class.php');

$collection = new Collection(array(2000, 2001, 2002));

$JSON = $collection->toJSON(FALSE);

echo $JSON;
?>