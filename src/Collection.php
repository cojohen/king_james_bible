<?php

 /**
  * @author Joe Cohen <joe@dingocode.com>
  */
namespace KJV\Bible;

require_once 'DB.php';
require_once 'Verse.php';

class Collection
{
    public $verses = array();
    public $omit_search_terms = array(" a ", " the ", " and ");
    public $search_result_size;
    private $book;
    private $chapter;

    public function __construct($ids = array())
    {
        if (count($ids) > 0) {
            $this->setByID($ids);
        }
    }

    /**
     * @param int[] ids
     */
    public function setByID($ids): void
    {
        foreach ($ids as $id) {
            $this->verses[] = new Verse($id);
        }
    }

    public function getBook(): string
    {
        return $this->book;
    }

    public function getChapter(): int
    {
        return $this->chapter;
    }
    /**
     * @param string book
     * @param int chapter
     *   ex. 'revelation' or 'genesis', 14
     */
    public function loadChapter($book = '', $chapter = 1): void
    {
        if ($book and $chapter) {
            $book = str_replace('_', ' ', $book);
            $db = new DB();

            // assuming valid book & chapter
            $this->book = ucfirst($book);
            $this->chapter = $chapter;

            // Get verse ids for this book and chapter
            $q = "SELECT text.id AS id FROM kjv.text LEFT JOIN kjv.books ON " .
            " text.book=books.id WHERE books.book='$book' AND text.chapter=$chapter " .
            "ORDER BY text.id LIMIT 180;";
            $rows = $db->query($q)->fetchAll();

            foreach ($rows as $row) {
                $ids[] = $row['id'];
            }

            $this->setByID($ids);
        }
    }
    /**
     * @param bool breaks
     *
     * returns string list
     */
    public function getListElements($breaks = false): string
    {
        $list = '';
        foreach ($this->verses as $verse) {
            $list .= $verse->getListItem;
            $list .= ($breaks ? "\n" : '');
        }
        return $list;
    }
    /**
     * @param int[] flags -- verses to flag
     */
    public function getBibleText($flags = array()): string
    {
        $page = '';
        foreach ($this->verses as $verse) {
            $page .= $verse->getPageText($flags);
        }
        return $page;
    }
    /**
     * @param bool breaks
     *
     * returns string JSON
     */
    public function getJSON($breaks = false): string
    {
        $JSON = '';

        if (count($this->verses) > 0) {
            $JSON .= '{ "collection" : [';
            foreach ($this->verses as $verse) {
                $JSON .= $verse->getJSON() . ',';
                $JSON .= ($breaks ? "\n" : '');
            }

            // Removes trailing comma
            $JSON = substr($JSON, 0, ($breaks ? -2 : -1));
            $JSON .= '], ';

            $JSON .= '"collection-size" : ' . count($this->verses) . ',';
            $JSON .= '"search-result-size" : ' . intval($this->search_result_size) . '';
            $JSON .= ' }';
        }

        return $JSON;
    }
    /**
     * @param string phrase
     *
     * returns int[] verseIDs
     */
    public function searchVerses($phrase = ''): void
    {
        $verseIDs = array();
        $db = new DB();
        $quotedSearchTerms = array();
        $db_rows = array();

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

            $this->setByID($verseIDs);
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
