<?php

/**
 * We check if an email address already exists in the db
 * @param string $email the email address
 * @param mixed $db the database connection
 * @return bool
 */
function mail_exists(string $email, $db) : bool
{
    $sql = "SELECT name FROM users WHERE email = ?";
    $query = $db->prepare($sql);

    $query->execute([$email]);

    $result = $query->fetchAll();

    if( count($result) > 0 ) return true;

    return false;
   
}