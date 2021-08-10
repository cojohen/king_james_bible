<?php

/**
 * @author Joe Cohen <joe@dingocode.com>
*/

namespace KJV\Bible;

require_once 'DB.php';

class Search {

    public int $search_result_size;
    public $omit_search_terms = array(" a ", " the ", " and ");

    /**
     * @param string phrase
     *
     * returns int[] verseIDs
     */
    public function searchVerses($phrase = ''): array
    {
        $quotedSearchTerms = array();
        $verseIDs = array();
        $db_rows = array();
        $db = new DB();
        
    

        // 1. Search literal phrases contained in double quotes
        if (preg_match_all('/"([^"]+)"/', $phrase, $quotedSearchTerms)) {
            foreach ($quotedSearchTerms[1] as $quote) {
                $sql = "SELECT text.id  FROM kjv.text WHERE (text.text COLLATE " .
                " utf8mb4_general_ci LIKE '%$quote%') LIMIT 20";

                $db_rows = array_merge($db_rows, $db->query($sql)->fetchAll());
            }
        }
        // 2. Search natural language mode for all phrases
        if (strlen($phrase) > 0) {
            // omit needless search terms
            $search = str_replace($this->omit_search_terms, ' ', $phrase);

            $sql = "SELECT DISTINCT text.id FROM kjv.text WHERE MATCH (`text`) " .
            " AGAINST ('$search' IN NATURAL LANGUAGE MODE) ORDER BY MATCH " .
            "(`text`) AGAINST ('$search' IN NATURAL LANGUAGE MODE) DESC LIMIT 20;";
            $db_rows = array_merge($db_rows, $db->query($sql)->fetchAll());

            // update results count
            $count_sql = "SELECT COUNT(text.id) AS `count` FROM kjv.text WHERE " .
            "MATCH (`text`) AGAINST ('$search' IN NATURAL LANGUAGE MODE) LIMIT 1";
            $count_row = $db->query($count_sql)->fetchArray();
        }
        // 3. Push IDs into verseIDs[] as ints
        if (count($db_rows)) {
            foreach ($db_rows as $row) {
                if (!in_array(intval($row["id"]), $verseIDs, true)) {
                    $verseIDs[] = intval($row["id"]);
                }
            }

            // update result size
            $this->search_result_size =
                (count($this->verses) > intval($count_row['count'])
                ? count($this->verses)
                : intval($count_row['count'])
            );
        } else {
            $this->search_result_size = 0;
        }
    }
}
