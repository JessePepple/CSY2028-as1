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

/**
 * Get all auctions in this category
 * @param $cat Category id
 * @param $db The database connection
 * @return array
 */
function get_auctions($cat, $db) : array
{
    $sql = "SELECT a.title, a.id, a.image, a.categoryId, a.description, a.endDate, c.name from auction AS a INNER JOIN category AS c ON a.categoryId = c.id WHERE a.categoryId = ?";
    $query = $db->prepare($sql);
    $query->execute([$cat]);
    $result = $query->fetchAll();

    if(!$result) return [];

    return $result;
}

/**
 * Gets a category
 * @param $cat the category id
 * @param $db the database connection
 * @return array
 */
function get_category($cat, $db): array
{
    $sql = "SELECT name, id FROM category where id = ?";
    $query = $db->prepare($sql);
    $query->execute([$cat]);

    $result = $query->fetchAll();

    if(!$result) return [];

    return $result[0];
}

/**
 * Gets all the categories available
 * @param $db the database connection
 * @return array
 */
function get_categories($db)
{
    $sql = "SELECT * FROM category";
    $query = $db->prepare($sql);
    $query->execute();

    $result = $query->fetchAll();

    if(!$result) return [];

    return $result;
}