<?php
/*
File Name: UserDBConnection.php

Version 1.0.2
CSC 478 Group Project
Group: FanSports
Wesley Elliot, Jeremy Jones, Ann Oesterle
Last Updated: 11/9/2016
*/
$usrDBName = 'root';
$usrDBPass = 'root';

//connect to mySQL userdb
try
{
    $pdo = new PDO("mysql:host=localhost; dbname=fansportsdb; charset=utf8mb4", $usrDBName, $usrDBPass); //connect to userdb
}
catch (PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>