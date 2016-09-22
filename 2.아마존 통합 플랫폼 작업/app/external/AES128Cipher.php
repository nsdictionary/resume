<?php
/*
 http://www.imcore.net | hosihito@gmail.com
 Developer. Kyoungbin Lee
 2012.05.25

 AES256 EnCrypt / DeCrypt
*/

/*
function AES_Encode($plain_text, $key)
{
    return base64_encode(openssl_encrypt($plain_text, "aes-128-ecb", $key, true, str_repeat(chr(0), 16)));
}

function AES_Decode($base64_text, $key)
{
    return openssl_decrypt(base64_decode($base64_text), "aes-128-ecb", $key, true, str_repeat(chr(0), 16));
}
*/


function AES_Encode($plain_text, $key)
{
    return base64_encode(openssl_encrypt($plain_text, "aes-128-cbc", $key, true, str_repeat(chr(0), 16)));
}

function AES_Decode($base64_text, $key)
{
    return openssl_decrypt(base64_decode($base64_text), "aes-128-cbc", $key, true, str_repeat(chr(0), 16));
}

?>