<?php
$pgSQLusername = "nfldb";
$pgSQLpass = "ProjectCSC478!";

try {
    $pgsqlpdo = new PDO("pgsql:host=localhost; dbname=nfldb;", $pgSQLusername, $pgSQLpass); //connect to userdb
	$pgsqlpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "<p style='color:red'>Error!: " . $e->getMessage() . "</p><br/>";
    die();
}
?>