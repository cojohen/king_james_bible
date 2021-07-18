<?php
/**
 *      Title:          collection.class.php
 *      Author:         Joe Cohen
 *      Contact:        <deskofjoe@gmail.com>
 *      GitHub:         https://github.com/cojohen
 * 
 *      Purpose:        A collection of verses.
 *            
 */
require(__DIR__.'/verse.class.php');

class Collection {

    public $verses = array();
    
    public function __construct($ids = array()) {
        if (count($ids) > 0)
            $this->setIDs($ids);
    }

    /**
     * @param $ids = int[]
     */
    public function setIDs($ids) {
        foreach ($ids as $id) {
            $this->verses[] = new Verse($id);
        }
    }

    public function toListElements($breaks = FALSE) {
        $list = '';
        
        foreach ($this->verses as $verse) {
            $list .= $verse->toListItem;
            $list .= ($breaks ? "\n" : '');
        }

        return $list;
    }
    
    public function toJSON($breaks = FALSE) {
        $JSON = '{ "collection" : [';
        foreach ($this->verses as $verse) {
            $JSON .= $verse->toJSON().',';
            $JSON .= ($breaks ? "\n" : '');
        }

        $JSON = substr($JSON, 0 , ($breaks ? -2 :-1));  // Removes trailing comma
        $JSON .= '] }';

        return $JSON;
    }


}