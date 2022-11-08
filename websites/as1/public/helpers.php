<?php

/**
 * We check if an email address already exists in the db
 * @param string $email the email address
 * @param mixed $db the database connection
 * @return boolean
 */
function mail_exists(string $email, $db) : boolean
{
    $sql = "SELECT name FROM users WHERE email = ?";
    $query = $db->prepare($sql);

    $query->execute([$email]);

    $result = $query->fetchAll();

    if( count($result) > 0 ) return true;

    return false;
   
}