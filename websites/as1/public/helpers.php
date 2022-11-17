<?php

/**
 * We check if an email address already exists in the db
 * @param string $email the email address
 * @param mixed $db the database connection
 * @param string $current_mail This would make the function return false when we are editing a user
 * @return bool
 */
function mail_exists(string $email, $db, string $current_mail = '') : bool
{
    $sql = "SELECT name FROM users WHERE email = ?";
    $query = $db->prepare($sql);

    $query->execute([$email]);

    $result = $query->fetchAll();

    if($current_mail == $email) return false;

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
    $sql = "SELECT a.title, a.id, a.image, a.categoryId, a.user_id, a.description, a.endDate, c.name from auction AS a INNER JOIN category AS c ON a.categoryId = c.id ORDER BY endDate LIMIT $limit";
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

/**
 * Returns the time remaining for an auction to run
 * @param string $end_date The auction end date
 */
function end_date(string $end_date)
{
    $today = new DateTime();
    $end = new DateTime($end_date);

    if($today >= $end) return 'Ended';

    $interval = $end->diff($today)->format('%a days %h hours %i minutes');

    return $interval;
}

/**
 * Gets the highest bid in an auction
 * @param int $auction_id The auction id
 * @param $db 
 */
function get_highest_bid(int $auction_id, $db)
{
    $sql = "SELECT amount FROM bids WHERE auction_id = ? ORDER BY amount DESC LIMIT 1";
    $query = $db->prepare($sql);

    $query->execute([$auction_id]);

    $result = $query->fetchAll();

    if(!$result) return '0.00';

    return number_format($result[0]['amount'], 2);
}

/**
 * Gets the reviews posted by a user
 * @param int $user_id
 * @param $db
 * @return array
 */
function get_user_reviews($user_id, $db)
{
    $sql = "SELECT r.id, r.user_id, r.reviewer_id, r.review_text, r.date_posted, u.name FROM review as r INNER JOIN users as u ON r.reviewer_id = u.id WHERE r.reviewer_id = ?";
    $query = $db->prepare($sql);
    $query->execute([$user_id]);

    $result = $query->fetchAll();

    if(!$result) return [];

    return $result;
}

/**
 * get reviews posted to a user
 * @param $user_id
 * @param $db
 */
function get_reviews($user_id, $db)
{
    $sql = "SELECT r.id, r.user_id, r.reviewer_id, r.review_text, r.date_posted, u.name FROM review as r INNER JOIN users as u ON r.reviewer_id = u.id WHERE r.user_id = ?";
    $query = $db->prepare($sql);
    $query->execute([$user_id]);

    $result = $query->fetchAll();

    if(!$result) return [];

    return $result;
}

// we want to make sure a user exists to be logged in our system

if(isset($_SESSION['id']))
{
    $this_user = get_user($_SESSION['id'], $db);
    if(!$this_user)
    {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
}

/**
 * Checks if a user is logged in
 * @return bool
 */
function is_logged() : bool
{
    if(!isset($_SESSION['id'])) return false;

    return true;
}

/**
 * Checks if a user is admin
 * @return bool
 */
function is_admin() : bool
{
    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) return true;

    return false;
}