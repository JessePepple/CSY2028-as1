<?php
require_once('head.php');
require_once('helpers.php');

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit;
}

if (!$_SESSION['is_admin'])
{
    include_once('header.php');
    echo('<p>You need to be an admin to view this page</p>');
    include_once('footer.php');
    exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("Location: manageAdmins.php");
    exit;
}

$user = get_user($_GET['id'], $db);

if(!$user)
{
    header("Location: manageAdmins.php");
    exit;
}

/**
 * Deletes all user bids
 * @param $id user id
 * @param $db
 */
function delete_user_bids($id, $db)
{
    $sql = "DELETE FROM bids WHERE user_id = ?";
    $query = $db->prepare($sql);

    return $query->execute([$id]);
}

/**
 * Deletes all auctions a user has
 * @param $id The user id
 * @param $db
 */
function delete_user_auctions($id, $db)
{
    $sql = "DELETE FROM auction WHERE user_id = ?";
    $query = $db->prepare($sql);

    return $query->execute([$id]);
}

/**
 * Deletes all reviews a user has
 * @param $id The user id
 * @param $db
 */
function delete_user_reviews($id, $db)
{
    $sql = "DELETE FROM review WHERE user_id = ? OR reviewer_id = ?";
    $query = $db->prepare($sql);

    return $query->execute([$id, $id]);
}

/**
 * Deletes a user
 * @param $id The user id to delete
 * @param $current_user The current user id, user cannot delete self
 * @param $db
 * @return bool 
 */
function delete_user(int $id, int $current_user, $db) : bool
{
    if($id == $current_user) return false;
    
    // delete all user bids
    delete_user_bids($id, $db);

    // delete all user auctions
    delete_user_auctions($id, $db);

    // delete all user reviews
    delete_user_reviews($id, $db);

    $sql = "DELETE FROM users WHERE id = ?";
    $query = $db->prepare($sql);

    if(!$query->execute([$id])) return false;

    return true;

}

if(delete_user($user['id'], $_SESSION['id'], $db))
{
    header('Location: manageAdmins.php?delete=true');
}
else
{
    header('Location: manageAdmins.php?delete=false');
}