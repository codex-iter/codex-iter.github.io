<?php
    
    //Function to check for invalid characters in the Username
    function checkInvalidCharacters($text) {
        
        return(bool) preg_match("/[^\w-.]/" , $text );
    }

    //Function to encrypt the users Password
    function encrypt_pswd($input , $rounds = 9 ){

        $salt = "";

        $saltChars = array_merge(range('A' , 'Z') , range('a' , 'z') , range('0' , '9') );

        for($i = 0 ; $i < 22 ; $i++)
        {
            $salt .= $saltChars[array_rand($saltChars)];
        }

        return crypt($input  , sprintf('$2a$%02d$' , $rounds) . $salt);
    }

?>