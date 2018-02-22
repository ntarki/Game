<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

function generateToken($length)
{
    $buf = '';
    for ($i = 0; $i < $length; ++$i) {
        $buf .= chr(mt_rand(0, 255));
    }
    return bin2hex($buf);
}




echo generateToken(20)."</br>";

echo generateToken(64)."</br>";


echo generateToken(128)."</br>";

echo generateToken(256)."</br>";




$dc=openssl_random_pseudo_bytes(20);
echo  $dc."</br>";
$dc= openssl_random_pseudo_bytes(64);
echo  $dc."</br>";
$dc=openssl_random_pseudo_bytes(128);
echo  $dc."</br>";
$dc=openssl_random_pseudo_bytes(256);
echo  $dc."</br>";

$dc=mcrypt_create_iv(20, MCRYPT_DEV_URANDOM);
echo  $dc."</br>";
$dc=mcrypt_create_iv(64, MCRYPT_DEV_URANDOM);
echo  $dc."</br>";
$dc=mcrypt_create_iv(128, MCRYPT_DEV_URANDOM);
echo  $dc."</br>";
$dc=mcrypt_create_iv(256, MCRYPT_DEV_URANDOM);
echo  $dc."</br>";



?>