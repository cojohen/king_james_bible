<?php

/**
 * @author Joe Cohen <joe@dingocode.com>
*/

namespace KJV\Bible;

require_once 'Verse.php';

class Bible
{
    /**
     * returns [] = [book] => 'Genesis', [chapter] => 2
     */
    public static function getNextChapter(string $book, int $chapter): array
    {
        // isValidReference($book = '', $chap = 0, $verse = 0)
        if (Verse::isValidReference($book, $chapter+1)) {
            return array(
                'book' => $book,
                'chapter' => $chapter+1
            );

        } else {
            $nextBook = self::getNextBook($book);

            if (Verse::isValidReference($nextBook, 1)) {
                return array(
                    'book' => $nextBook,
                    'chapter' => 1
                );

            } else {
                return array();
            }
        }
    }

    public static function getPreviousChapter(string $book, int $chapter): array
    {
        // isValidReference($book = '', $chap = 0, $verse = 0)
        if ($chapter-1 and Verse::isValidReference($book, $chapter-1)) {
            return array(
                'book' => $book,
                'chapter' => $chapter-1
            );

        } else {
            $previousBook = self::getPreviousBook($book);
            $lastChapter = self::getLastChapter($previousBook);

            if (Verse::isValidReference($previousBook, self::getLastChapter($previousBook))) {
                return array(
                    'book' => $previousBook,
                    'chapter' => $lastChapter
                );

            } else {
                return array();
            }
        }
    }
    public static function getLastChapter($book): int
    {
        $q = "SELECT max(text.chapter) AS id FROM kjv.text LEFT JOIN kjv.books " .
            "ON text.book=books.id WHERE books.book='$book' LIMIT 1";
        $db = new DB();
        $row = $db->query($q)->fetchArray();

        return (isset($row['id']) ? intval($row['id']) : 0);
    }

    public static function getNextBook(string $book): string 
    {
        $q = "SELECT books.book FROM kjv.books WHERE (books.id-1) = " .
            "(SELECT books.id FROM kjv.books WHERE books.book='$book')";
        $db = new DB();
        $row = $db->query($q)->fetchArray();

        return (isset($row['book']) ? $row['book'] : '');
    }
    
    public static function getPreviousBook(string $book): string 
    {
        $q = "SELECT books.book FROM kjv.books WHERE (books.id+1) = " .
            "(SELECT books.id FROM kjv.books WHERE books.book='$book')";
        $db = new DB();
        $row = $db->query($q)->fetchArray();

        return (isset($row['book']) ? $row['book'] : '');
    }
    public static function getBooksAsLinks($testament = 'all'): string
    {
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

            $links .= '<li><a class="" href="' .
                str_replace(' ', '_', lcfirst($book)) .
                '/">' . $book . '</a></li>';
        }

        return $links;
    }
}
