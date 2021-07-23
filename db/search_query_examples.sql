
--Select string literal (exact match)
SELECT text.id  FROM kjv.text WHERE (text.text COLLATE utf8mb4_general_ci LIKE '%jesus wept%');

--Select expansive language search (similar terms)
SELECT books.book, text.chapter, text.verse, text.text  FROM kjv.text LEFT JOIN books ON text.book=books.id WHERE MATCH (`text`) AGAINST ("jesus wept" IN NATURAL LANGUAGE MODE) ORDER BY MATCH (`text`) AGAINST ("jesus wept" IN NATURAL LANGUAGE MODE) DESC LIMIT 16;

SELECT DISTINCT text.id FROM kjv.text WHERE (text.text COLLATE utf8mb4_general_ci LIKE '%jesus wept%') OR MATCH (`text`) AGAINST ("jesus wept" IN NATURAL LANGUAGE MODE) ORDER BY MATCH (`text`) AGAINST ("jesus wept" IN NATURAL LANGUAGE MODE) DESC LIMIT 16;