<?php
require_once('head.php');
require_once('helpers.php');
$page_title = 'New Category';

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit;
}
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("Location: adminCategories.php");
    exit;
}
if (!$_SESSION['is_admin'])
{
    include_once('header.php');
    echo('<p>You need to be an admin to view this page</p>');
    include_once('footer.php');
    exit;
}

$category = get_category($_GET['id'], $db);

if(!$category)
{
    header("Location: adminCategories.php");
    exit;
}
/**
 * Deletes a category
 * @param int $id The category id
 * @param $db The database connection
 */
function delete_category(int $id, $db)
{
    $auctions = get_auctions($id, $db);

    foreach($auctions as $row)
    {
        delete_auction_bids($row['id'], $db);
    }

    delete_category_auctions($id, $db);
    $sql = "DELETE FROM category WHERE id = ?";
    $query = $db->prepare($sql);

    return $query->execute([$id]);
}

/**
 * Deletes all auctions in a category
 * @param $id The category id
 * @param $db
 */
function delete_category_auctions(int $id, $db)
{
    $sql = "DELETE FROM auction WHERE categoryId = ?";
    $query = $db->prepare($sql);

    return $query->execute([$id]);
}

/**
 * Deletes all bids this auction has
 * @param $id auction id
 * @param $db
 */
function delete_auction_bids($id, $db)
{
    $sql = "DELETE FROM bids WHERE auction_id = ?";
    $query = $db->prepare($sql);

    return $query->execute([$id]);
}


if(delete_category($category['id'], $db))
{
    header("Location: adminCategories.php?delete=true");
}
else
{
    echo "An internal error occured";
}
   
$db = null;