<?php
try {
$db = new PDO('mysql:dbname=assignment1;host=mysql', 'v.je', 'v.je');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    die("Unable to connect. Error: {$e->getMessage()}");
}
