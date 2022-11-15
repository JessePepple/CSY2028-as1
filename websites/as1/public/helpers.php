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
    $sql = "SELECT a.title, a.id, a.image, a.categoryId, a.user_id, a.description, a.endDate, c.name from auction AS a INNER JOIN category AS c ON a.categoryId = c.id WHERE a.categoryId = ?";
    $query = $db->prepare($sql);
    $query->execute([$cat]);
    $result = $query->fetchAll();

    if(!$result) return [];

    return $result;
}

/**
 * Get latest auctions 
 */
function get_auctions_top($db, $limit = 10) : array
{
    $sql = "SELECT a.title, a.id, a.image, a.categoryId, a.user_id, a.description, a.endDate, c.name from auction AS a INNER JOIN category AS c ON a.categoryId = c.id LIMIT $limit";
    $query = $db->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();

    if(!$result) return [];

    return $result;
}

/**
 * Get an auction with more info
 * @param $id auction id
 * @param $db
 */
function get_auction_view($id, $db)
{

    $sql = "SELECT a.title, a.id, a.image, a.categoryId, a.user_id, a.description, a.endDate, c.name from auction AS a INNER JOIN category AS c ON a.categoryId = c.id WHERE a.id = ?";
    $query = $db->prepare($sql);
    $query->execute([$id]);
    $result = $query->fetchAll();

    if(!$result) return [];

    return $result[0];
}

/**
 * Gets an auction
 * @param $id the auction id
 * @param $db the database connection
 * @return array
 */
function get_auction($id, $db): array
{
    $sql = "SELECT * FROM auction where id = ?";
    $query = $db->prepare($sql);
    $query->execute([$id]);

    $result = $query->fetchAll();

    if(!$result) return [];

    return $result[0];
}

/**
 * get a user profile
 * @param $user_id user id
 * @param $db
 */
function get_user($user_id, $db)
{
    $sql = "SELECT * FROM users WHERE id = ?";
    $query = $db->prepare($sql);
    $query->execute([$user_id]);

    $result = $query->fetchAll();

    if(!$result) return [];

    return $result[0];
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

/**
 * We use this to populate a form with values
 * @param string $name The form name
 * @param string $value2 Optional What value to use if the form is empty
 * @return string
 */
function form_value(string $name, string $value2 = '')
{
    $value = $_REQUEST[$name] ?? null;

    if(isset($value)) return $value;

    if(isset($value2)) return $value2;

    return '';
}