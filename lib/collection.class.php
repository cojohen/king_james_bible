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
    public $omit_search_terms = array(" a ", " the ", " and ");
    
    public function __construct($ids = array()) {
        if (count($ids) > 0)
            $this->setByID($ids);
    }

    /**
     * @param int[] ids
     */
    public function setByID($ids) {
        foreach ($ids as $id) {
            $this->verses[] = new Verse($id);
        }
    }
    /**
     * @param bool breaks
     * 
     * returns string list
     */
    public function toListElements($breaks = FALSE) {
        $list = '';
        
        foreach ($this->verses as $verse) {
            $list .= $verse->toListItem;
            $list .= ($breaks ? "\n" : '');
        }

        return $list;
    }
    /**
     * @param bool breaks
     * 
     * returns string JSON
     */
    public function toJSON($breaks = FALSE) {
        $JSON = '';
        
        if (count($this->verses) > 0) {
            $JSON .= '{ "collection" : [';
            foreach ($this->verses as $verse) {
                $JSON .= $verse->toJSON().',';
                $JSON .= ($breaks ? "\n" : '');
            }

            $JSON = substr($JSON, 0 , ($breaks ? -2 :-1));  // Removes trailing comma
            $JSON .= '] }';
        }
        return $JSON;
    }
    /**
     * @param string phrase
     * 
     * returns int[] verseIDs
     */
    public function searchVerses($phrase = '') {
        $verseIDs = array();
        $db = new db();
        $quotedSearchTerms = array();
        $db_rows = array();

        // 1. Search literal phrases contained in double quotes
        if (preg_match_all('/"([^"]+)"/', $phrase, $quotedSearchTerms)) {
            foreach ($quotedSearchTerms[1] as $quote) {
                $sql = "SELECT text.id  FROM kjv.text WHERE (text.text COLLATE utf8mb4_general_ci LIKE '%$quote%') LIMIT 20";

                $db_rows = array_merge($db_rows, $db->query($sql)->fetchAll());
            }
        }
        // 2. Search natural language mode for all phrases
        if (strlen($phrase) > 0) {

            $search = str_replace($this->omit_search_terms, ' ', $phrase);   // omit needless search terms
            
            $sql = "SELECT DISTINCT text.id FROM kjv.text WHERE MATCH (`text`) AGAINST ('$search' IN NATURAL LANGUAGE MODE) ORDER BY MATCH (`text`) AGAINST ('$search' IN NATURAL LANGUAGE MODE) DESC LIMIT 20;";

            $db_rows = array_merge($db_rows, $db->query($sql)->fetchAll());
        }
        // 3. Push IDs into verseIDs[] as ints
        if (count($db_rows)) {
            foreach ($db_rows as $row) {
                if ( !in_array(intval($row["id"]), $verseIDs, true) ) {
                    $verseIDs[] = intval($row["id"]);
                }
            }
        }

        $this->setByID($verseIDs);
    }

}