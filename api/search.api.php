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

    $response ='{
                    "results": [
                        {
                            "book" : "Genesis",
                            "chap" :  "1",
                            "verse":  "1",
                            "text" :  "In the beginning God created the heaven and the earth."
                        },
                        {
                            "book" : "Genesis",
                            "chap" :  "2",
                            "verse":  "1",
                            "text" :  "Thus the heavens and the earth were finished, and all the host of them."
                        },
                        {
                            "book" : "Genesis",
                            "chap" :  "3",
                            "verse":  "1",
                            "text" :  "Now the serpent was more subtil than any beast of the field which the LORD God had made. And he said unto the woman, Yea, hath God said, Ye shall not eat of every tree of the garden?"
                        },
                    ]
                }';

    echo $response;

?>