<?php

    function checkInvalidCharacters($text) {
        
        return(bool) preg_match("/[^\w-.]/" , $text );
    }

?>