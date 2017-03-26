<?php
$usrDBName = 'bd9045819725';
$usrDBPass = 'Lzr5LklT9z1K24F3oBft';

//connect to mySQL userdb
try
{
    $pdo = new PDO("mysql:host=localhost; dbname=userdb; charset=utf8mb4", $usrDBName, $usrDBPass); //connect to userdb
}
catch (PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


?>